<?php

namespace App\Repository;

use App\Entity\Favory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favory>
 *
 * @method Favory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Favory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Favory[]    findAll()
 * @method Favory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favory::class);
    }

    public function add(Favory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Favory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}
