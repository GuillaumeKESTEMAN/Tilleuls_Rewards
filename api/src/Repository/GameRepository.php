<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CommonRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function persistAndFlush(object $entity, bool $flush = false): bool
    {
        $lastGame = $this->findOneByPlayer($entity->getPlayer());

        if (null !== $lastGame && date_diff($lastGame->getCreationDate(), new \DateTime)->d < 1) {
            return false;
        }

        $entity->setCreationDate(new \DateTime);
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return true;
    }

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByPlayer($player): ?Game
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.player = :val')
            ->orderBy('p.creationDate')
            ->setParameter('val', $player)
            ->orderBy('p.creationDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
