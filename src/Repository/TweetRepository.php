<?php

namespace App\Repository;

use App\Entity\Tweet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tweet>
 *
 * @method Tweet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tweet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tweet[]    findAll()
 * @method Tweet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TweetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tweet::class);
    }

    public function add(Tweet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tweet $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
            ->getOneOrNullResult()
            ;
    }

    /**
     * @return Tweet[] Returns an array of Tweet objects
     */
    public function findByPlayerTweets($player, int $maxResults = 10): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.player = :player')
            ->setParameter('player', $player)
            ->orderBy('t.creationDate', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult()
            ;
    }
}
