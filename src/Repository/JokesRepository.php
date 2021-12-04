<?php

namespace App\Repository;

use App\Entity\Jokes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Exception;

/**
 * @method Jokes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jokes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jokes[]    findAll()
 * @method Jokes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JokesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jokes::class);
    }

    public function findOneRandom()
    {
        $count = $this->createQueryBuilder('j')
            ->select('COUNT(j)')
            ->getQuery()
            ->getSingleScalarResult();

        $randJoke = $this->getRandJoke($count);

        if ($randJoke == null)
        {
            for ($i = 0; $randJoke == null; $i++)
            {
                $randJoke = $this->getRandJoke($count);
            }
        }
        return $randJoke;
    }

    private function getRandJoke(int $count)
    {
        return $this->createQueryBuilder('j')
            ->where('j.id = :id')
            ->setMaxResults(1)
            ->setParameter('id', rand(0, $count - 1))
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Jokes[] Returns an array of Jokes objects
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
    public function findOneBySomeField($value): ?Jokes
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
