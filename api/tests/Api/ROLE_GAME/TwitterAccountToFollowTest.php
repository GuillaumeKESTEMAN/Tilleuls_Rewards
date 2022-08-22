<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TwitterAccountToFollow;
use App\Tests\Security\LoginTest;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class TwitterAccountToFollowTest extends ApiTestCase
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

        $response = self::createClient()->request('GET', '/twitter_account_to_follows', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TwitterAccountToFollow',
            '@id' => '/twitter_account_to_follows',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);

        $this->assertCount(2, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(TwitterAccountToFollow::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetTwitterAccountToFollow(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['username' => '@symfony']);

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
    public function testCreateTwitterAccountToFollow(): void
    {
        $token = LoginTest::getGameLoginToken();

        self::createClient()->request('POST', '/twitter_account_to_follows', [
            'auth_bearer' => $token,
            'json' => [
                'username' => '@ApiPlatform',
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
    public function testUpdateTwitterAccountToFollow(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['username' => '@symfony']);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'username' => '@symfony',
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
    public function testDeleteTwitterAccountToFollow(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['username' => '@symfony']);

        self::createClient()->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
