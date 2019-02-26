<?php

namespace App\Repository;

use App\Entity\Reviewer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Reviewer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reviewer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reviewer[]    findAll()
 * @method Reviewer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewerRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reviewer::class);
    }

    // /**
    //  * @return Reviewer[] Returns an array of Reviewer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Reviewer
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
