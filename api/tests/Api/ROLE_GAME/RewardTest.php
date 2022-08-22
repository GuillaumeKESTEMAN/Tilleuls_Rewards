<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Reward;
use App\Tests\Security\LoginTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class RewardTest extends ApiTestCase
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

        self::createClient()->request('GET', '/rewards', ['auth_bearer' => $token]);

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
    public function testGetReward(): void
    {
        $token = LoginTest::getGameLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

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
    public function testCreateReward(): void
    {
        $token = LoginTest::getGameLoginToken();

        self::createClient()->request('POST', '/rewards', [
                'auth_bearer' => $token,
                'json' => [
                    'distributed' => true,
                ]
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testUpdateReward(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        self::createClient()->request('PUT', $iri, [
                'auth_bearer' => $token,
                'json' => [
                    'distributed' => false,
                ],
            ]
        );

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
    public function testDeleteReward(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        self::createClient()->request('DELETE', $iri, [
                'auth_bearer' => $token,
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }
}
