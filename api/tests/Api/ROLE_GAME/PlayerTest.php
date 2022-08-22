<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

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
        $token = LoginTest::getGameLoginToken();

        self::createClient()->request('GET', '/players', ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
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
        $token = LoginTest::getGameLoginToken();

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
        $token = LoginTest::getGameLoginToken();

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
        $token = LoginTest::getGameLoginToken();

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
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(Player::class, ['username' => '@TestAccount']);

        self::createClient()->request('DELETE', $iri, [
                'auth_bearer' => $token
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }
}
