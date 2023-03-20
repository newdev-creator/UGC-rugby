<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserChild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Helpers\CategoryHelper;

/**
 * @extends ServiceEntityRepository<UserChild>
 *
 * @method UserChild|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserChild|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserChild[]    findAll()
 * @method UserChild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserChildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserChild::class);
    }

    public function save(UserChild $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserChild $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // GET CHILDREN
    public function getChildren(array $rolesUser, bool $isActive = true): array
    {
        // Convert boolean to integer
        $isActiveValue = (bool)$isActive;

        $qb = $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'c.firstName',
                'c.lastName',
                'c.birthday',
                'cat.name AS categoryName',
                'u.firstName AS userFirstName',
                'u.lastName AS userLastName',
            )
            ->leftJoin('c.category', 'cat')
            ->leftJoin('c.user', 'u')
        ;

        // Filter by active users
        $qb->andWhere('c.isActive = :isActive')
            ->setParameter('isActive', $isActiveValue);

        // Filter by child's category
        $categoryValues = CategoryHelper::getCategoriesFromRoles($rolesUser);

        if (!empty($categoryValues)) {
            $qb->andWhere('cat.name IN (:categoryValues)')
                ->setParameter('categoryValues', $categoryValues);
        }

        return $qb->getQuery()->getResult();
    }
}
