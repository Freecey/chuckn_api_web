<?php

namespace App\Repository;

use App\Entity\JokesRatings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JokesRatings|null find($id, $lockMode = null, $lockVersion = null)
 * @method JokesRatings|null findOneBy(array $criteria, array $orderBy = null)
 * @method JokesRatings[]    findAll()
 * @method JokesRatings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JokesRatingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JokesRatings::class);
    }



    public function findOneLess24($value): ?JokesRatings
    {
        $date = new \DateTime();
        $date->modify('-24 hour');

        return $this->createQueryBuilder('jr')
            ->andWhere(':joke_id MEMBER OF  jr.joke')
            ->andWhere('jr.created_at > :date')
            ->setParameters(array('date'=> $date, 'joke_id' => $value))
//            ->setParameter('date', $date)
//            ->setParameter('joke', $value->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return JokesRatings[] Returns an array of JokesRatings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JokesRatings
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
