<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TweetReply;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends CommonRepository<TweetReply>
 *
 * @method TweetReply|null find($id, $lockMode = null, $lockVersion = null)
 * @method TweetReply|null findOneBy(array $criteria, array $orderBy = null)
 * @method TweetReply[]    findAll()
 * @method TweetReply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TweetReplyRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, TweetReply::class, $logger);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByName($name): ?TweetReply
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.name = :val')
            ->setParameter('val', $name)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
