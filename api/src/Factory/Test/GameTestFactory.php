<?php

namespace App\Factory\Test;

use App\Entity\Game;
use App\Factory\PlayerFactory;
use App\Factory\TweetFactory;
use App\Repository\GameRepository;
use DateTime;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Game>
 *
 * @method static Game|Proxy createOne(array $attributes = [])
 * @method static Game[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Game|Proxy find(object|array|mixed $criteria)
 * @method static Game|Proxy findOrCreate(array $attributes)
 * @method static Game|Proxy first(string $sortedField = 'id')
 * @method static Game|Proxy last(string $sortedField = 'id')
 * @method static Game|Proxy random(array $attributes = [])
 * @method static Game|Proxy randomOrCreate(array $attributes = [])
 * @method static Game[]|Proxy[] all()
 * @method static Game[]|Proxy[] findBy(array $attributes)
 * @method static Game[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Game[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static GameRepository|RepositoryProxy repository()
 * @method Game|Proxy create(array|callable $attributes = [])
 */
final class GameTestFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'tweet' => TweetFactory::new(),
            'player' => PlayerFactory::random(),
            'creationDate' => new DateTime('2022-01-01 12:30:00.000000')
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Game $game): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Game::class;
    }
}
