<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Game;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends CommonRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @ORM\Entity
 * @ORM\Table(name="game_repository")
 */
final class GameRepository extends CommonRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByPlayer($player): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.player = :val')
            ->setParameter('val', $player)
            ->orderBy('g.playDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneById(int $id): ?Game
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id = :val')
            ->setParameter('val', $id)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getDaysCount(DateTime $afterDate, DateTime $beforeDate = new DateTime()): array
    {
        return $this->createQueryBuilder('g')
            ->select('count(g.id) as nbrGames, DATE(g.playDate) as date')
            ->andWhere('g.playDate <= :beforeDate')
            ->andWhere('g.playDate >= :afterDate')
            ->setParameter('beforeDate', $beforeDate)
            ->setParameter('afterDate', $afterDate)
            ->groupBy('date')
            ->orderBy('date', 'DESC')
            ->getQuery()
            ->getScalarResult();
    }
}
