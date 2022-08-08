<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\TwitterAccountToFollow;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

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
    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, TwitterAccountToFollow::class, $logger);
    }

    /**
     * @return TwitterAccountToFollow[]
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
