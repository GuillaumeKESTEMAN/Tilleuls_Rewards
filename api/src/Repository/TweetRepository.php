<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Tweet;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CommonRepository<Tweet>
 *
 * @method Tweet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tweet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tweet[]    findAll()
 * @method Tweet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweetRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tweet::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByTweetId(string $value): ?Tweet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.tweetId = :val')
            ->setParameter('val', $value)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
