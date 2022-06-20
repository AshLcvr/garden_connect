<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    private $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Annonce::class);
        $this->paginator = $paginator;
    }

    public function add(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return PaginatorInterface
     */
    public function findBySearch(SearchData $search)
    {
        $query = $this->getSearchQuery($search)->getQuery();
        return $this->paginator->paginate($query, $search->page, 10);
    }

    /**
     * @param SearchData $search
     * @return integer[]
     */
    public function findMinMax(SearchData $search) :array
    {
        $results = $this->getSearchQuery($search)
            ->select('MIN(a.price) as min', 'MAX(a.price) as max')
            ->getQuery()
            ->getScalarResult();
        return [(int)$results[0]['min'],(int)$results[0]['max']];
    }

    /**
     * @param SearchData $search
     * @return integer[]
     */
    public function getSearchQuery(SearchData $search) : QueryBuilder
    {
        if (!empty($search->q) ||!empty($search->category) || !empty($search->min) || !empty($search->max) ) {
            $query = $this
                ->createQueryBuilder('a')
                ->select('c', 'a')
                ->join('a.subcategory', 'c')
                ->andWhere('a.actif = 1')
                ->orderBy('a.created_at', 'DESC');

            if (!empty($search->q)) {
                $query = $query
                    ->andWhere('a.title LIKE :q')
                    ->setParameter('q', "%{$search->q}%");
            }

            if (!empty($search->category)) {
                $query = $query
                    ->andWhere('c.parent_category IN (:category)')
                    ->setParameter(':category', $search->category);
            }

            if (!empty($search->subcategory)) {
                $query = $query
                    ->andWhere('c.id IN (:subcategory)')
                    ->setParameter(':subcategory', $search->subcategory);
            }

            if (!empty($search->min)) {
                $query = $query
                    ->andWhere('a.price >= :min')
                    ->setParameter('min', "$search->min");
            }

            if (!empty($search->max)) {
                $query = $query
                    ->andWhere('a.price <= :max')
                    ->setParameter('max', "$search->max");
            }
        }else {
                $query = $this
                    ->createQueryBuilder('a')
                    ->andWhere('a.actif = 1')
                    ->orderBy('a.created_at', 'DESC');
            }
         return $query;
    }
    public function newAnnonces($value1): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.created_at > :val1')
            ->setParameter('val1', $value1)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getActifAnnoncesBoutique($value1, $value3): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.boutique = :val1')
            ->setParameter('val1', $value1)
            ->andWhere('a.actif = true')
            ->andWhere('a.id != :val3')
            ->setParameter('val3', $value3)
            ->getQuery()
            ->getResult()
        ;
    }
}
