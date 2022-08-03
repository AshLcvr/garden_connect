<?php

namespace App\Repository;

use App\Entity\ImagesAnnonces;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ImagesAnnonces>
 *
 * @method ImagesAnnonces|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImagesAnnonces|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImagesAnnonces[]    findAll()
 * @method ImagesAnnonces[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImagesAnnoncesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImagesAnnonces::class);
    }

    public function add(ImagesAnnonces $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ImagesAnnonces $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ImagesAnnonces[] Returns an array of ImagesAnnonces objects
     */
    public function findImagesbyAnnonceId($id): array
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.annonce = :val')
            ->setParameter('val', $id)
            ->getQuery()
            ->getResult()
        ;
    }
}
