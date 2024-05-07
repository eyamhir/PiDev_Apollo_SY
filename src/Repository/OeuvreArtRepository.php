<?php

namespace App\Repository;

use App\Entity\OeuvreArt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OeuvreArt>
 *
 * @method OeuvreArt|null find($id, $lockMode = null, $lockVersion = null)
 * @method OeuvreArt|null findOneBy(array $criteria, array $orderBy = null)
 * @method OeuvreArt[]    findAll()
 * @method OeuvreArt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OeuvreArtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OeuvreArt::class);
    }
    // Méthode pour récupérer les œuvres par l'ID du portfolio
    public function findByPortfolioId(int $portfolioId): ?array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.id_portfolio = :portfolioId')
            ->setParameter('portfolioId', $portfolioId)
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return OeuvreArt[] Returns an array of OeuvreArt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OeuvreArt
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
