<?php

namespace App\Factory\Test;

use App\Entity\Lot;
use App\Repository\LotRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Lot>
 *
 * @method static Lot|Proxy createOne(array $attributes = [])
 * @method static Lot[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Lot|Proxy find(object|array|mixed $criteria)
 * @method static Lot|Proxy findOrCreate(array $attributes)
 * @method static Lot|Proxy first(string $sortedField = 'id')
 * @method static Lot|Proxy last(string $sortedField = 'id')
 * @method static Lot|Proxy random(array $attributes = [])
 * @method static Lot|Proxy randomOrCreate(array $attributes = [])
 * @method static Lot[]|Proxy[] all()
 * @method static Lot[]|Proxy[] findBy(array $attributes)
 * @method static Lot[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Lot[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static LotRepository|RepositoryProxy repository()
 * @method Lot|Proxy create(array|callable $attributes = [])
 */
final class LotTestFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => 'Lot de test',
            'quantity' => self::faker()->biasedNumberBetween(),
            'message' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Lot $lot): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Lot::class;
    }
}
