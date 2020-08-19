<?php

namespace App\Repository;

use App\Entity\DmsFolder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DmsFolder|null find($id, $lockMode = null, $lockVersion = null)
 * @method DmsFolder|null findOneBy(array $criteria, array $orderBy = null)
 * @method DmsFolder[]    findAll()
 * @method DmsFolder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DmsFolderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DmsFolder::class);
    }

    // /**
    //  * @return DmsFolder[] Returns an array of DmsFolder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DmsFolder
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
