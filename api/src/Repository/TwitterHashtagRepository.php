<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TwitterHashtag;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends CommonRepository<TwitterHashtag>
 *
 * @method TwitterHashtag|null find($id, $lockMode = null, $lockVersion = null)
 * @method TwitterHashtag|null findOneBy(array $criteria, array $orderBy = null)
 * @method TwitterHashtag[]    findAll()
 * @method TwitterHashtag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TwitterHashtagRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, TwitterHashtag::class, $logger);
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
            ->getResult();
    }
}
