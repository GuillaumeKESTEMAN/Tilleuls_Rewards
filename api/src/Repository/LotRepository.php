<?php

namespace App\Repository;

use App\Entity\Lot;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * @extends CommonRepository<Lot>
 *
 * @method Lot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lot[]    findAll()
 * @method Lot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LotRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry, private readonly LoggerInterface $logger)
    {
        parent::__construct($registry, Lot::class);
    }

    /**
     * @throws NonUniqueResultException|Exception
     */
    public function getRandom(int $numberOfLotReturn = 1): array
    {
        /** @var Lot[] $lots */
        $lots = $this->createQueryBuilder('l')
            ->where('l.quantity > 0')
            ->getQuery()
            ->getResult();

        $dataReturn = [];

        for ($i = 0; $i < $numberOfLotReturn; $i++) {
            $totalQuantity = 0;
            foreach ($lots as $lot) {
                if ($lot->getQuantity() <= 0) {
                    continue;
                }

                $lot->min = $totalQuantity;
                $totalQuantity += $lot->getQuantity();
                $lot->max = $totalQuantity;
            }

            if ($totalQuantity <= 0) {
                return $dataReturn;
            }

            $randomNumber = random_int(0, $totalQuantity - 1);

            $filteredLots = array_filter($lots, static fn(Lot $lot) => $lot->min <= $randomNumber && $lot->max > $randomNumber);
            $lot = reset($filteredLots);

            if (false === $lot) {
                $this->logger->warning('No Lot found during random Lot search !');
                return $dataReturn;
            }

            $lot->setQuantity($lot->getQuantity() - 1);
            $this->persistAndFlush($lot, true);

            $dataReturn[] = $lot;
        }

        return $dataReturn;
    }
}
