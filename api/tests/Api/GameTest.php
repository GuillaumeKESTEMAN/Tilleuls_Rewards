<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Game;
use App\Tests\Security\LoginTest;
use DateTime;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GameTest extends ApiTestCase
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

        $response = static::createClient()->request('GET', '/api/games', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Game',
            '@id' => '/api/games',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 60,
            'hydra:view' => [
                '@id' => '/api/games?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/api/games?page=1',
                'hydra:last' => '/api/games?page=3',
                'hydra:next' => '/api/games?page=2',
            ],
        ]);

        $this->assertCount(20, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(Game::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetGame(): void
    {
        $token = LoginTest::getLoginToken();

        $iri = $this->findIriBy(Game::class, ['creationDate' => new DateTime('2022-01-01 12:30:00.000000')]);

        static::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Game',
            '@id' => $iri,
            '@type' => 'https://schema.org/VideoGame',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateGame(): void
    {
        static::createClient()->request('POST', '/api/games', ['json' => [
            'creationDate' => new DateTime('2022-01-01 12:35:00.000000'),
        ]]);

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testUpdateGame(): void
    {
        $iri = $this->findIriBy(Game::class, ['creationDate' => new DateTime('2022-01-01 12:30:00.000000')]);

        static::createClient()->request('PUT', $iri, ['json' => [
            'creationDate' => new DateTime('2022-01-01 12:35:00.000000'),
        ]]);

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteGame(): void
    {
        $iri = $this->findIriBy(Game::class, ['creationDate' => new DateTime('2022-01-01 12:30:00.000000')]);

        static::createClient()->request('DELETE', $iri);

        self::assertResponseStatusCodeSame(405);
    }
}
