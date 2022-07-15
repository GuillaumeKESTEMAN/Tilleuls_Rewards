<?php

namespace App\Factory;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Player>
 *
 * @method static Player|Proxy createOne(array $attributes = [])
 * @method static Player[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Player|Proxy find(object|array|mixed $criteria)
 * @method static Player|Proxy findOrCreate(array $attributes)
 * @method static Player|Proxy first(string $sortedField = 'id')
 * @method static Player|Proxy last(string $sortedField = 'id')
 * @method static Player|Proxy random(array $attributes = [])
 * @method static Player|Proxy randomOrCreate(array $attributes = [])
 * @method static Player[]|Proxy[] all()
 * @method static Player[]|Proxy[] findBy(array $attributes)
 * @method static Player[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Player[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static PlayerRepository|RepositoryProxy repository()
 * @method Player|Proxy create(array|callable $attributes = [])
 */
final class PlayerFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'name' => self::faker()->unique()->name(),
            'username' => self::faker()->unique()->name(),
            'twitterAccountId' => self::faker()->uuid(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Player $player): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Player::class;
    }
}
