<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_ADMIN;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Game;
use App\Tests\Security\LoginTest;
use DateTime;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class GameTest extends ApiTestCase
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

        $response = self::createClient()->request('GET', '/games', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Game',
            '@id' => '/games',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 60,
            'hydra:view' => [
                '@id' => '/games?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/games?page=1',
                'hydra:last' => '/games?page=3',
                'hydra:next' => '/games?page=2',
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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Game::class, ['playDate' => new DateTime('2022-01-01 12:30:00.000000')]);

        self::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Game',
            '@id' => $iri,
            '@type' => 'https://schema.org/VideoGame',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateGame(): void
    {
        $token = LoginTest::getAdminLoginToken();

        self::createClient()->request('POST', '/games', [
            'auth_bearer' => $token,
                'json' => [
                    'playDate' => new DateTime('2022-01-01 12:35:00.000000'),
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
    public function testUpdateGame(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Game::class, ['playDate' => new DateTime('2022-01-01 12:00:00.000000')]);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'score' => 10,
            ],]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'score' => 10,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testFailedToReupdateGame(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Game::class, ['playDate' => new DateTime('2022-01-01 12:00:00.000000')]);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'score' => 11,
            ],]);

        self::assertResponseStatusCodeSame(403);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testDeleteGame(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Game::class, ['playDate' => new DateTime('2022-01-01 12:00:00.000000')]);

        self::createClient()->request('DELETE', $iri, [
                'auth_bearer' => $token,
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }
}
