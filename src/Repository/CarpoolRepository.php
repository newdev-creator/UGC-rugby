<?php

namespace App\Repository;

use App\Entity\Carpool;
use App\Helpers\CategoryHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carpool>
 *
 * @method Carpool|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carpool|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carpool[]    findAll()
 * @method Carpool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarpoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carpool::class);
    }

    public function save(Carpool $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Carpool $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // GET CARPOOL
    public function getCarpools(array $rolesUser, bool $isActive = true): array
    {
        // Convert boolean to integer
        $isActiveValue = (bool)$isActive;

        $qb = $this->createQueryBuilder('c')
            ->select(
                'c.id',
                'c.status',
                'c.date',
                'c.address',
                'c.postalCode',
                'c.city',
                'c.nbPlace',
                'u.firstName',
                'u.lastName',
                'COUNT(uc.id) AS nbChildren',
                'cc.name AS category'
            )
            ->leftJoin('c.users', 'u')
            ->leftJoin('c.child', 'uc')
            ->leftJoin('uc.category', 'cc')
            ->groupBy('c.id', 'u.firstName', 'u.lastName', 'cc.name')
        ;

        // Filter by active users
        $qb->andWhere('c.isActive = :isActive')
            ->setParameter('isActive', $isActiveValue);
        // Filter by child's category
        $categoryValues = CategoryHelper::getCategoriesFromRoles($rolesUser);

        if (!empty($categoryValues)) {
            $qb->andWhere('cc.name IN (:categoryValues)')
                ->setParameter('categoryValues', $categoryValues);
        }


        return $qb->getQuery()->getResult();
    }
}
