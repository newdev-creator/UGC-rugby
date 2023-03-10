<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Event>
 *
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Event::class);
        $this->paginator = $paginator;
    }

    public function save(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Event $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // GET EVENTS
    public function getEvents(bool $isActive = true): array
    {
        $qb = $this->createQueryBuilder('e')
            ->select(
                'e.id',
                'e.status',
                'e.title',
                'e.date',
                'e.address',
                'e.postalCode',
                'e.city',
                'e.nbMinus',
                'e.nbRegistrant',
                'e.isActive'
            )
        ;

        if ($isActive) {
            $qb->where('e.isActive = :val')
                ->setParameter('val', true);
        } else {
            $qb->where('e.isActive = :val')
                ->setParameter('val', false);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * @param SearchData $search
     * @return PaginationInterface
     */
    public function findSearch(SearchData $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('e')
            ->select('c', 'e')
            ->join('e.categories', 'c')
            ->andWhere('e.isActive = :isActive')
            ->setParameter('isActive', 1);

        // Search by title
        if (!empty($search->q)) {
            $query = $query
                ->andWhere('e.title LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        // Filter Categories
        if (!empty($search->categories)) {
            $query = $query
                ->andWhere('c.id IN (:categories)')
                ->setParameter('categories', $search->categories);
        }
        $query = $query->getQuery();
        return $this->paginator->paginate(
            $query,
            $search->page,
            12,
        );
    }
}
