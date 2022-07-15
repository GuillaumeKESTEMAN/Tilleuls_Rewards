<?php

namespace App\Repository;

use App\Entity\TwitterAccountToFollow;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CommonRepository<TwitterAccountToFollow>
 *
 * @method TwitterAccountToFollow|null find($id, $lockMode = null, $lockVersion = null)
 * @method TwitterAccountToFollow|null findOneBy(array $criteria, array $orderBy = null)
 * @method TwitterAccountToFollow[]    findAll()
 * @method TwitterAccountToFollow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TwitterAccountToFollowRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TwitterAccountToFollow::class);
    }

//    /**
//     * @return TwitterAccountToFollow[] Returns an array of TwitterAccountToFollow objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TwitterAccountToFollow
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
