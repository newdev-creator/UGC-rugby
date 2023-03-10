<?php

namespace App\Repository;

use App\Entity\User;
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

    // GET USER
    public function getUsers(bool $isActive = true): array
    {
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
            )
            ->leftJoin('u.child', 'uc')
        ;

        if ($isActive) {
            $qb->where('u.isActive = :val')
                ->setParameter('val', true);
        } else {
            $qb->where('u.isActive = :val')
                ->setParameter('val', false);
        }

        $results = $qb->getQuery()->getResult();

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
                'children' => []
            ];

            if (!empty($result['childFirstName']) && !empty($result['childLastName'])) {
                $user['children'][] = [
                    'firstName' => $result['childFirstName'],
                    'lastName' => $result['childLastName']
                ];
            }

            if (isset($usersWithChildren[$userId])) {
                $usersWithChildren[$userId]['children'] = array_merge($usersWithChildren[$userId]['children'], $user['children']);
            } else {
                $usersWithChildren[$userId] = $user;
            }
        }

        return array_values($usersWithChildren);
    }



//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
