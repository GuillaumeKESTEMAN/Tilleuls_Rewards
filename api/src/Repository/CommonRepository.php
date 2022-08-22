<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;

abstract class CommonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entityClass, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @throws Exception
     */
    public function persistAndFlush(object $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function removeAndFlush(object $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($entity);

            if ($flush) {
                $this->getEntityManager()->flush();
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
            throw $e;
        }

    }
}
