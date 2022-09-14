<?php

namespace App\Repository;

use App\Entity\Subcategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Subcategory>
 *
 * @method Subcategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subcategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subcategory[]    findAll()
 * @method Subcategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubcategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Subcategory::class);
    }

    public function add(Subcategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Subcategory $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Subcategory Returns a random Subcategory object
     */
    public function randomSubcategory()
    {
        $randomSubcats = ['Amande','Raisin noir', 'Œuf de poule','Citrouilles', 'Oignon','Pistache', 'Noisette', 'Fraise','Framboise', 'Melon','Poire', 'Pomme','Blé', 'Menthe','Muscade', 'Persil','Rhubarbe', 'Miel','Champignon', 'Carottes','Haricots', 'Manioc','Poireau', 'Tomate'];
        $randomSubcat = $randomSubcats[random_int(0,17)];

        return $this->createQueryBuilder('s')
            ->where('s.title = :val' )
            ->setParameter('val', $randomSubcat)
            ->orderBy(' RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }
}
