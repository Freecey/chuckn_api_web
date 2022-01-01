<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function findOneLess7day($joke, $ipaddress): ?Report
    {
        $date = new \DateTime();
        $date->modify('-7 day');

        return $this->createQueryBuilder('r')
            ->andWhere(':joke MEMBER OF  r.joke')
            ->andWhere('r.created_At > :date')
            ->andWhere('r.ip = :ipaddress')
            ->setParameters(array('date'=> $date, 'joke' => $joke, 'ipaddress' => $ipaddress))
//            ->setParameter('date', $date)
//            ->setParameter('joke', $value->getId())
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return Report[] Returns an array of Report objects
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
    public function findOneBySomeField($value): ?Report
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
