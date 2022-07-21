<?php

namespace App\Factory\Test;

use App\Entity\TwitterHashtag;
use App\Repository\TwitterHashtagRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<TwitterHashtag>
 *
 * @method static TwitterHashtag|Proxy createOne(array $attributes = [])
 * @method static TwitterHashtag[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TwitterHashtag|Proxy find(object|array|mixed $criteria)
 * @method static TwitterHashtag|Proxy findOrCreate(array $attributes)
 * @method static TwitterHashtag|Proxy first(string $sortedField = 'id')
 * @method static TwitterHashtag|Proxy last(string $sortedField = 'id')
 * @method static TwitterHashtag|Proxy random(array $attributes = [])
 * @method static TwitterHashtag|Proxy randomOrCreate(array $attributes = [])
 * @method static TwitterHashtag[]|Proxy[] all()
 * @method static TwitterHashtag[]|Proxy[] findBy(array $attributes)
 * @method static TwitterHashtag[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static TwitterHashtag[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TwitterHashtagRepository|RepositoryProxy repository()
 * @method TwitterHashtag|Proxy create(array|callable $attributes = [])
 */
final class TwitterHashtagTestFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'hashtag' => '#getTest',
            'active' => self::faker()->boolean(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(TwitterHashtag $twitterHashtag): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TwitterHashtag::class;
    }
}
