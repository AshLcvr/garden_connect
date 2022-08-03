<?php

namespace App\Repository;

use App\Entity\ImagesBoutique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImagesBoutique>
 *
 * @method ImagesBoutique|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesBoutique|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesBoutique[]    findAll()
 * @method ImagesBoutique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesBoutiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesBoutique::class);
    }

    public function add(ImagesBoutique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ImagesBoutique $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ImagesBoutique[] Returns an array of ImagesBoutique objects
     */
    public function findImagesbyBoutiqueId($id): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.boutique = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }
}
