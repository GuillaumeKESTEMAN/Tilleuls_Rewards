<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Lot;
use App\Tests\Security\LoginTest;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class LotTest extends ApiTestCase
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

        self::createClient()->request('GET', '/lots', ['auth_bearer' => $token]);

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
    public function testGetLot(): void
    {
        $token = LoginTest::getGameLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Lot::class, ['name' => 'Lot de test pour game']);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Lot',
            '@id' => $iri,
            '@type' => 'Lot',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testCreateLot(): void
    {
        $token = LoginTest::getGameLoginToken();

        self::createClient()->request('POST', '/lots', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Nouveau lot de test par game',
                'quantity' => 3,
                'message' => "C'est un super lot !",
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
    public function testUpdateLot(): void
    {
        $token = LoginTest::getGameLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Lot::class, ['name' => 'Lot de test pour game']);

        $client->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Lot de test pour game 2.0',
                'quantity' => 2,
                'message' => 'C\'est un autre super lot de game !',
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
    public function testDeleteLotForGame(): void
    {
        $token = LoginTest::getGameLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Lot::class, ['name' => 'Supprime moi game !']);

        $client->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
