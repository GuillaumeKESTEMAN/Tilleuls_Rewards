<?php

namespace App\Repository;

use App\Entity\TwitterHashtag;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CommonRepository<TwitterHashtag>
 *
 * @method TwitterHashtag|null find($id, $lockMode = null, $lockVersion = null)
 * @method TwitterHashtag|null findOneBy(array $criteria, array $orderBy = null)
 * @method TwitterHashtag[]    findAll()
 * @method TwitterHashtag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TwitterHashtagRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TwitterHashtag::class);
    }

    /**
     * @return TwitterHashtag[] Returns an array of TwitterHashtag objects
     */
    public function getAllActive(): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.active = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?TwitterHashtag
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
