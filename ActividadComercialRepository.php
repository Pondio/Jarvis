<?php

namespace App\Repository;

use App\Entity\ActividadComercial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ActividadComercial|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActividadComercial|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActividadComercial[]    findAll()
 * @method ActividadComercial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActividadComercialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActividadComercial::class);
    }

    // /**
    //  * @return ActividadComercial[] Returns an array of ActividadComercial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActividadComercial
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
