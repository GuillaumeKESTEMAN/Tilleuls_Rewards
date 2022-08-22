<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_ADMIN;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Player;
use App\Tests\Security\LoginTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class PlayerTest extends ApiTestCase
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
        $token = LoginTest::getAdminLoginToken();

        $response = self::createClient()->request('GET', '/players', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Player',
            '@id' => '/players',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 60,
            'hydra:view' => [
                '@id' => '/players?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/players?page=1',
                'hydra:last' => '/players?page=3',
                'hydra:next' => '/players?page=2',
            ],
        ]);

        $this->assertCount(20, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(Player::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetPlayer(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Player::class, ['username' => '@TestAccount']);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Player',
            '@id' => $iri,
            '@type' => 'Player',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreatePlayer(): void
    {
        $token = LoginTest::getAdminLoginToken();

        self::createClient()->request('POST', '/players', [
                'auth_bearer' => $token,
                'json' => [
                    'name' => 'Twitter Account Name',
                    'username' => '@TwitterAccountUsername',
                    'twitterAccountId' => '123456789',
                ]
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testUpdatePlayer(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Player::class, ['username' => '@TestAccount']);

        self::createClient()->request('PUT', $iri, [
                'auth_bearer' => $token,
                'json' => [
                    'name' => 'New Twitter Account Name',
                ]
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testDeletePlayer(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Player::class, ['username' => '@TestAccount']);

        self::createClient()->request('DELETE', $iri, [
                'auth_bearer' => $token
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }
}
