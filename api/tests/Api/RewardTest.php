<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Reward;
use App\Tests\Security\LoginTest;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class RewardTest extends ApiTestCase
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

        $response = static::createClient()->request('GET', '/rewards', ['auth_bearer' => $token]);

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
        $token = LoginTest::getLoginToken();

        $client = static::createClient();
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
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateReward(): void
    {
        $response = static::createClient()->request('POST', '/rewards', ['json' => [
            'distributed' => true,
        ]]);

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
        $token = LoginTest::getLoginToken();

        $client = static::createClient();

        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        $client->request('PUT', $iri, [
            'json' => [
                'distributed' => false,
            ],
            'auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteReward(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Reward::class, ['distributed' => true]);

        $client->request('DELETE', $iri);

        self::assertResponseStatusCodeSame(405);
    }
}
