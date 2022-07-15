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
    public function getRandom(int $numberOfLotReturn = 1): ?array
    {
        $rows = $this->createQueryBuilder('l')
            ->where('l.quantity > 0')
            ->getQuery()
            ->getResult();

        $totalQuantity = 0;
        $numbersLot = [];

        $dataReturn = [];

        for ($i = 0; $i < $numberOfLotReturn; $i++) {
            foreach ($rows as $row) {
                if ($row->getQuantity() <= 0) {
                    continue;
                }

                $data = [];
                $data['id'] = $row->getId();
                $data['min'] = $totalQuantity;
                $totalQuantity += $row->getQuantity();
                $data['max'] = $totalQuantity;

                $numbersLot[] = $data;
            }

            if ($totalQuantity <= 0) {
                return null;
            }

            $randomNumber = rand(0, $totalQuantity - 1);
            $lot = null;

            foreach ($numbersLot as $numberLot) {
                if ($numberLot['min'] <= $randomNumber && $numberLot['max'] > $randomNumber) {
                    foreach ($rows as $key => $row) {
                        if($row->getId() === $numberLot['id']) {
                            $row->setQuantity($row->getQuantity() - 1);

                            $this->add($row, true);

                            $rows[$key] = $row;
                            $lot = $row;
                            break;
                        }
                    }
                }
            }
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
