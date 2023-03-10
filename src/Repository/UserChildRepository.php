<?php

namespace App\Repository;

use App\Entity\UserChild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function getChildren(bool $isActive = true): array
    {
        $qb = $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'c.firstName',
                'c.lastName',
                'c.birthday',
                'cat.name AS categoryName',
                'u.firstName AS userFirstName',
                'u.lastName AS userLastName'
            )
            ->leftJoin('c.category', 'cat')
            ->leftJoin('c.user', 'u')
        ;

        if ($isActive) {
            $qb->where('c.isActive = :val')
                ->setParameter('val', true);
        } else {
            $qb->where('c.isActive = :val')
                ->setParameter('val', false);
        }

        return $qb->getQuery()->getResult();
    }


//    /**
//     * @return UserChild[] Returns an array of UserChild objects
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

//    public function findOneBySomeField($value): ?UserChild
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
