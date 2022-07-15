<?php

namespace App\Factory;

use App\Entity\Reward;
use App\Repository\RewardRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Reward>
 *
 * @method static Reward|Proxy createOne(array $attributes = [])
 * @method static Reward[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Reward|Proxy find(object|array|mixed $criteria)
 * @method static Reward|Proxy findOrCreate(array $attributes)
 * @method static Reward|Proxy first(string $sortedField = 'id')
 * @method static Reward|Proxy last(string $sortedField = 'id')
 * @method static Reward|Proxy random(array $attributes = [])
 * @method static Reward|Proxy randomOrCreate(array $attributes = [])
 * @method static Reward[]|Proxy[] all()
 * @method static Reward[]|Proxy[] findBy(array $attributes)
 * @method static Reward[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Reward[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static RewardRepository|RepositoryProxy repository()
 * @method Reward|Proxy create(array|callable $attributes = [])
 */
final class RewardFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'lot' => LotFactory::random(),
            'game' => GameFactory::random(),
            'winDate' => self::faker()->dateTime,
            'distributed' => self::faker()->boolean(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Reward $reward): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Reward::class;
    }
}
