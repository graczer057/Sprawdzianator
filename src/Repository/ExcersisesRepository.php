<?php

namespace App\Repository;

use App\Entity\Excersises;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Excersises|null find($id, $lockMode = null, $lockVersion = null)
 * @method Excersises|null findOneBy(array $criteria, array $orderBy = null)
 * @method Excersises[]    findAll()
 * @method Excersises[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExcersisesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Excersises::class);
    }

    // /**
    //  * @return Excersises[] Returns an array of Excersises objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Excersises
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
