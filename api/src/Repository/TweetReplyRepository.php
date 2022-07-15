<?php

namespace App\Repository;

use App\Entity\TweetReply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CommonRepository<TweetReply>
 *
 * @method TweetReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method TweetReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method TweetReply[]    findAll()
 * @method TweetReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweetReplyRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TweetReply::class);
    }

//    /**
//     * @return TweetReply[] Returns an array of TweetReply objects
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

//    public function findOneBySomeField($value): ?TweetReply
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
