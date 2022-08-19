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
        $token = LoginTest::getLoginToken();

        $response = static::createClient()->request('GET', '/twitter_account_to_follows', ['auth_bearer' => $token]);

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
        $token = LoginTest::getLoginToken();

        $client = static::createClient();
        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['username' => '@coopTilleuls']);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TwitterAccountToFollow',
            '@id' => $iri,
            '@type' => 'TwitterAccountToFollow',
            'username' => '@coopTilleuls',
            'name' => 'Les-Tilleuls.coop',
        ]);
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

        $invalidTwitterUsernameAccount = 'testInvalidUser';

        static::createClient()->request('POST', '/twitter_account_to_follows', [
            'auth_bearer' => $token,
            'json' => [
                'username' => '@'.$invalidTwitterUsernameAccount,   // doesn't exist
                'active' => false,
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'username: Le compte Twitter "@'.$invalidTwitterUsernameAccount.'" n\'existe pas',
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

        $response = static::createClient()->request('POST', '/twitter_account_to_follows', [
            'auth_bearer' => $token,
            'json' => [
                'username' => '@ApiPlatform',
                'active' => false,
            ],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TwitterAccountToFollow',
            '@type' => 'TwitterAccountToFollow',
            'username' => '@ApiPlatform',
            'active' => false,
        ]);

        $this->assertMatchesRegularExpression('~^/twitter_account_to_follows/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(TwitterAccountToFollow::class);
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
                'username' => '@coopTilleuls',
                'active' => true,
            ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'username' => '@coopTilleuls',
            'active' => true,
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
