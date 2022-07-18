<?php

namespace App\Repository;

use App\Entity\Lot;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lot::class);
    }

    /**
     * @throws NonUniqueResultException
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

            $filteredLots = array_filter($lots, static fn (Lot $lot) => $lot->min <= $randomNumber && $lot->max > $randomNumber);
            $lot = reset($filteredLots);

            if (false === $lot) {
                // TODO add logs
                return [];
            }

            $lot->setQuantity($lot->getQuantity() - 1);
            $this->persistAndFlush($lot, true);

            $dataReturn[] = $lot;
        }

        return $dataReturn;
    }

//    /**
//     * @return Lot[] Returns an array of Lot objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Lot
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
