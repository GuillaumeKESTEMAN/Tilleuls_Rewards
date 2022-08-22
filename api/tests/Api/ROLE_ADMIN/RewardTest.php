<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_ADMIN;

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
        $token = LoginTest::getAdminLoginToken();

        $response = self::createClient()->request('GET', '/rewards', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Reward',
            '@id' => '/rewards',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 60,
            'hydra:view' => [
                '@id' => '/rewards?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/rewards?page=1',
                'hydra:last' => '/rewards?page=3',
                'hydra:next' => '/rewards?page=2',
            ],
        ]);

        $this->assertCount(20, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(Reward::class);
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
        $token = LoginTest::getAdminLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Reward',
            '@id' => $iri,
            '@type' => 'Reward',
        ]);
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
        $token = LoginTest::getAdminLoginToken();

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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        self::createClient()->request('PUT', $iri, [
                'auth_bearer' => $token,
                'json' => [
                    'distributed' => false,
                ],
            ]
        );

        self::assertResponseIsSuccessful();
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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        self::createClient()->request('DELETE', $iri, [
                'auth_bearer' => $token,
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }
}
