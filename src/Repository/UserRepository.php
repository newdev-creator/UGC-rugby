<?php

namespace App\Repository;

use App\Entity\User;
use App\Helpers\CategoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function save(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->save($user, true);
    }

    // Filter users by child's category and by role of connected user
    public function getUsers(array $rolesUser, bool $isActive = true): array
    {
        // Convert boolean to integer
        $isActiveValue = (bool)$isActive;

        // Create query builder
        $qb = $this->createQueryBuilder('u')
            ->select(
                'u.id',
                'u.email',
                'u.roles',
                'u.lastName',
                'u.firstName',
                'u.phone',
                'u.address',
                'u.postalCode',
                'u.city',
                'uc.firstName AS childFirstName',
                'uc.lastName AS childLastName',
                'ucc.name AS childCategory',
            )
            ->leftJoin('u.child', 'uc')
            ->leftJoin('uc.category', 'ucc')
        ;

        // Filter by active users
        $qb->andWhere('u.isActive = :isActive')
            ->setParameter('isActive', $isActiveValue);

        // Filter by child's category
        $categoryValues = CategoryHelper::getCategoriesFromRoles($rolesUser);

        if (!empty($categoryValues)) {
            $qb->andWhere('ucc.name IN (:categoryValues)')
                ->setParameter('categoryValues', $categoryValues);
        }

        // Retrieve results
        $results = $qb->getQuery()->getResult();

        // Group users by id
        $usersWithChildren = [];

        foreach ($results as $result) {
            $userId = $result['id'];
            $user = [
                'id' => $userId,
                'email' => $result['email'],
                'roles' => $result['roles'],
                'lastName' => $result['lastName'],
                'firstName' => $result['firstName'],
                'phone' => $result['phone'],
                'address' => $result['address'],
                'postalCode' => $result['postalCode'],
                'city' => $result['city'],
                'children' => [],
                'childCategory' => $result['childCategory']
            ];

            // Add child to user
            if (!empty($result['childFirstName']) && !empty($result['childLastName'])) {
                $user['children'][] = [
                    'firstName' => $result['childFirstName'],
                    'lastName' => $result['childLastName']
                ];
            }

            // Add user to array
            if (isset($usersWithChildren[$userId])) {
                $usersWithChildren[$userId]['children'] = array_merge($usersWithChildren[$userId]['children'], $user['children']);
            } else {
                $usersWithChildren[$userId] = $user;
            }
        }

        return array_values($usersWithChildren);
    }
}
