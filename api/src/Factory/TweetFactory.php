<?php

namespace App\Factory;

use App\Entity\Tweet;
use App\Repository\TweetRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Tweet>
 *
 * @method static Tweet|Proxy createOne(array $attributes = [])
 * @method static Tweet[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Tweet|Proxy find(object|array|mixed $criteria)
 * @method static Tweet|Proxy findOrCreate(array $attributes)
 * @method static Tweet|Proxy first(string $sortedField = 'id')
 * @method static Tweet|Proxy last(string $sortedField = 'id')
 * @method static Tweet|Proxy random(array $attributes = [])
 * @method static Tweet|Proxy randomOrCreate(array $attributes = [])
 * @method static Tweet[]|Proxy[] all()
 * @method static Tweet[]|Proxy[] findBy(array $attributes)
 * @method static Tweet[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Tweet[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TweetRepository|RepositoryProxy repository()
 * @method Tweet|Proxy create(array|callable $attributes = [])
 */
final class TweetFactory extends ModelFactory
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function getDefaults(): array
    {
        return [
            'tweetId' => self::faker()->unique()->uuid,
            'player' => PlayerFactory::random()
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Tweet $tweet): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Tweet::class;
    }
}
