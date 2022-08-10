<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TwitterAccountToFollow;
use App\Tests\Security\LoginTest;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TwitterAccountToFollowTest extends ApiTestCase
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
        $token = LoginTest::getLoginToken();

        $response = static::createClient()->request('GET', '/api/twitter_account_to_follows', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/TwitterAccountToFollow',
            '@id' => '/api/twitter_account_to_follows',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);

        $this->assertCount(1, $response->toArray()['hydra:member']);

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
        $token = LoginTest::getLoginToken();

        $client = static::createClient();
        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['username' => '@me']);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/TwitterAccountToFollow',
            '@id' => $iri,
            '@type' => 'TwitterAccountToFollow',
        ]);
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
        $token = LoginTest::getLoginToken();

        $response = static::createClient()->request('POST', '/api/twitter_account_to_follows', [
            'auth_bearer' => $token,
            'json' => [
                "username" => "@coopTilleuls",
                "active" => true
            ]
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/TwitterAccountToFollow',
            '@type' => 'TwitterAccountToFollow',
            'username' => '@coopTilleuls',
            'active' => true
        ]);

        $this->assertMatchesRegularExpression('~^/api/twitter_account_to_follows/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(TwitterAccountToFollow::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateInvalidTwitterAccountToFollow(): void
    {
        $token = LoginTest::getLoginToken();

        $invalidTwitterUsernameAccount = 'invalidTwitterUsernameAccount';

        static::createClient()->request('POST', '/api/twitter_account_to_follows', [
            'auth_bearer' => $token,
            'json' => [
                "username" => "@" . $invalidTwitterUsernameAccount,   # not exists for the moment
                "active" => false
            ]
        ]);

        self::assertResponseStatusCodeSame(400);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Error',
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'The `username` query parameter value [' . $invalidTwitterUsernameAccount . '] does not match ^[A-Za-z0-9_]{1,15}$',
        ]);
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
        $token = LoginTest::getLoginToken();

        $client = static::createClient();

        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['username' => '@coopTilleuls']);

        $client->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'username' => '@symfony',
                'active' => false
            ]
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'username' => '@coopTilleuls',
            'active' => false
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testDeleteTwitterAccountToFollow(): void
    {
        $token = LoginTest::getLoginToken();

        $client = static::createClient();
        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['username' => '@coopTilleuls']);


        $client->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(204);

        $this->assertNull(
            static::getContainer()->get('doctrine')->getRepository(TwitterAccountToFollow::class)->findOneBy(['username' => '@coopTilleuls'])
        );
    }
}
