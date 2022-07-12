<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Tweet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function add(Game $game, bool $flush = false): bool
    {
        $lastGame = $this->findOneByPlayer($game->getPlayer());

        if (null === $lastGame || date_diff($lastGame->getCreationDate(), new \DateTime)->d >= 1) {
            $game->setCreationDate(new \DateTime);
            $this->getEntityManager()->persist($game);
            $game->setUrl($_ENV["GAME_URL"]);

            if ($flush) {
                $this->getEntityManager()->flush();

                $game->setUrl($game->getUrl() . $game->getId());
                $this->getEntityManager()->persist($game);
                $this->getEntityManager()->flush();
            }
            return true;
        }
        return false;
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
