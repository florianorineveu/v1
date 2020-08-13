<?php

namespace App\Repository;

use App\Entity\GithubActivity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GithubActivity|null find($id, $lockMode = null, $lockVersion = null)
 * @method GithubActivity|null findOneBy(array $criteria, array $orderBy = null)
 * @method GithubActivity[]    findAll()
 * @method GithubActivity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GithubActivityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GithubActivity::class);
    }

    // /**
    //  * @return GithubActivity[] Returns an array of GithubActivity objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GithubActivity
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
