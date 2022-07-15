<?php

namespace App\Factory;

use App\Entity\Game;
use App\Repository\GameRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

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
final class GameFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            'tweet' => TweetFactory::new(),
            'player' => PlayerFactory::random(),
            'url' => $_ENV['GAME_URL'] . self::faker()->uuid,
            'creationDate' => self::faker()->dateTime
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
