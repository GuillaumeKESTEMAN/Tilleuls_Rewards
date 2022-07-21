<?php

namespace App\Factory\Test;

use App\Entity\TweetReply;
use App\Repository\TweetReplyRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<TweetReply>
 *
 * @method static TweetReply|Proxy createOne(array $attributes = [])
 * @method static TweetReply[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static TweetReply|Proxy find(object|array|mixed $criteria)
 * @method static TweetReply|Proxy findOrCreate(array $attributes)
 * @method static TweetReply|Proxy first(string $sortedField = 'id')
 * @method static TweetReply|Proxy last(string $sortedField = 'id')
 * @method static TweetReply|Proxy random(array $attributes = [])
 * @method static TweetReply|Proxy randomOrCreate(array $attributes = [])
 * @method static TweetReply[]|Proxy[] all()
 * @method static TweetReply[]|Proxy[] findBy(array $attributes)
 * @method static TweetReply[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static TweetReply[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static TweetReplyRepository|RepositoryProxy repository()
 * @method TweetReply|Proxy create(array|callable $attributes = [])
 */
final class TweetReplyTestFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'name' => 'on_new_game',
            'message' => self::faker()->text(),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(TweetReply $tweetReply): void {})
        ;
    }

    protected static function getClass(): string
    {
        return TweetReply::class;
    }
}
