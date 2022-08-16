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

    public function persistAndFlush(object $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->persist($entity);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }

        if ($flush) {
            try {
                $this->getEntityManager()->flush();
            } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }
    }

    public function removeAndFlush(object $entity, bool $flush = false): void
    {
        try {
            $this->getEntityManager()->remove($entity);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e->getTrace());
        }

        if ($flush) {
            try {
                $this->getEntityManager()->flush();
            } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }
    }
}
