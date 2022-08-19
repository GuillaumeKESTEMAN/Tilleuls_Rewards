<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tweet;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends CommonRepository<Tweet>
 *
 * @method Tweet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tweet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tweet[]    findAll()
 * @method Tweet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TweetRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, Tweet::class, $logger);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByTweetId(string $value): ?Tweet
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.tweetId = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findLastTweet(): ?Tweet
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.creationDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
