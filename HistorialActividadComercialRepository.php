<?php

namespace App\Repository;

use App\Entity\HistorialActividadComercial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistorialActividadComercial|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistorialActividadComercial|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistorialActividadComercial[]    findAll()
 * @method HistorialActividadComercial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistorialActividadComercialRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistorialActividadComercial::class);
    }

    // /**
    //  * @return HistorialActividadComercial[] Returns an array of HistorialActividadComercial objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistorialActividadComercial
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
