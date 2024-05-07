<?php

namespace App\Repository;

use App\Entity\Enchers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Enchers>
 *
 * @method Enchers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Enchers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Enchers[]    findAll()
 * @method Enchers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnchersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Enchers::class);
    }

//    /**
//     * @return Enchers[] Returns an array of Enchers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Enchers
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
