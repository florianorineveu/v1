<?php

namespace App\Repository;

use App\Entity\TemplateBlock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<\App\Entity\Block\TemplateBlock>
 *
 * @method TemplateBlock|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateBlock|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateBlock[]    findAll()
 * @method TemplateBlock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateBlockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateBlock::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TemplateBlock $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TemplateBlock $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return TemplateBlock[] Returns an array of TemplateBlock objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TemplateBlock
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
