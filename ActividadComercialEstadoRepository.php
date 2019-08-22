<?php

namespace App\Repository;

use App\Entity\ActividadComercialEstado;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ActividadComercialEstado|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActividadComercialEstado|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActividadComercialEstado[]    findAll()
 * @method ActividadComercialEstado[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActividadComercialEstadoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActividadComercialEstado::class);
    }

    // /**
    //  * @return ActividadComercialEstado[] Returns an array of ActividadComercialEstado objects
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
    public function findOneBySomeField($value): ?ActividadComercialEstado
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
