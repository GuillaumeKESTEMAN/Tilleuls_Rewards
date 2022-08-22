<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TwitterHashtag;
use App\Tests\Security\LoginTest;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class TwitterHashtagTest extends ApiTestCase
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetCollection(): void
    {
        $token = LoginTest::getGameLoginToken();

        $response = self::createClient()->request('GET', '/twitter_hashtags', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TwitterHashtag',
            '@id' => '/twitter_hashtags',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 60,
            'hydra:view' => [
                '@id' => '/twitter_hashtags?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/twitter_hashtags?page=1',
                'hydra:last' => '/twitter_hashtags?page=3',
                'hydra:next' => '/twitter_hashtags?page=2',
            ],
        ]);

        $this->assertCount(20, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(TwitterHashtag::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetTwitterHashtag(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TwitterHashtag::class, ['hashtag' => '#symfony']);

        self::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testCreateTwitterHashtag(): void
    {
        $token = LoginTest::getGameLoginToken();

        $response = self::createClient()->request('POST', '/twitter_hashtags', [
            'auth_bearer' => $token,
            'json' => [
                'hashtag' => '#test',
                'active' => false,
            ],
        ]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testUpdateTwitterHashtag(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TwitterHashtag::class, ['hashtag' => '#symfony']);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'hashtag' => '#symfony2.0',
                'active' => true,
            ],
        ]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testDeleteTwitterHashtag(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TwitterHashtag::class, ['hashtag' => '#symfony']);

        self::createClient()->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
