<?php

namespace App\Repository;

use App\Entity\NewJokes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NewJokes|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewJokes|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewJokes[]    findAll()
 * @method NewJokes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewJokesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewJokes::class);
    }

    // /**
    //  * @return NewJokes[] Returns an array of NewJokes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NewJokes
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
