<?php

namespace App\Factory\Test;

use App\Entity\TwitterAccountToFollow;
use App\Repository\TwitterAccountToFollowRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TwitterAccountToFollow>
 *
 * @method static TwitterAccountToFollow|Proxy createOne(array $attributes = [])
 * @method static TwitterAccountToFollow[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TwitterAccountToFollow|Proxy find(object|array|mixed $criteria)
 * @method static TwitterAccountToFollow|Proxy findOrCreate(array $attributes)
 * @method static TwitterAccountToFollow|Proxy first(string $sortedField = 'id')
 * @method static TwitterAccountToFollow|Proxy last(string $sortedField = 'id')
 * @method static TwitterAccountToFollow|Proxy random(array $attributes = [])
 * @method static TwitterAccountToFollow|Proxy randomOrCreate(array $attributes = [])
 * @method static TwitterAccountToFollow[]|Proxy[] all()
 * @method static TwitterAccountToFollow[]|Proxy[] findBy(array $attributes)
 * @method static TwitterAccountToFollow[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static TwitterAccountToFollow[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TwitterAccountToFollowRepository|RepositoryProxy repository()
 * @method TwitterAccountToFollow|Proxy create(array|callable $attributes = [])
 */
final class TwitterAccountToFollowTestFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'twitterAccountName' => self::faker()->name(),
            'twitterAccountUsername' => '@me',
            'twitterAccountId' => self::faker()->text(),
            'active' => self::faker()->boolean(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(TwitterAccountToFollow $twitterAccountToFollow): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TwitterAccountToFollow::class;
    }
}
