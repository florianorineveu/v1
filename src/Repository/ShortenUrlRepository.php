<?php

namespace App\Repository;

use App\Entity\ShortenUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ShortenUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortenUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortenUrl[]    findAll()
 * @method ShortenUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortenUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortenUrl::class);
    }
}
