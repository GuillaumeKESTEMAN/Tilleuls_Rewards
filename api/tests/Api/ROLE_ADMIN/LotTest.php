<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_ADMIN;

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
        $token = LoginTest::getAdminLoginToken();

        $response = self::createClient()->request('GET', '/lots', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Lot',
            '@id' => '/lots',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 62,
            'hydra:view' => [
                '@id' => '/lots?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/lots?page=1',
                'hydra:last' => '/lots?page=4',
                'hydra:next' => '/lots?page=2',
            ],
        ]);

        $this->assertCount(20, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(Lot::class);
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
        $token = LoginTest::getAdminLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Lot::class, ['name' => 'Lot de test']);

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
    public function testCreateLotWithInvalidQuantity(): void
    {
        $token = LoginTest::getAdminLoginToken();

        self::createClient()->request('POST', '/lots', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Nouveau lot de test',
                'quantity' => -12,
                'message' => "C'est un super lot !",
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'quantity: This value should be either positive or zero.',
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
        $token = LoginTest::getAdminLoginToken();

        $response = self::createClient()->request('POST', '/lots', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Nouveau lot de test',
                'quantity' => 3,
                'message' => "C'est un super lot !",
            ],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Lot',
            '@type' => 'Lot',
            'name' => 'Nouveau lot de test',
            'quantity' => 3,
            'message' => "C'est un super lot !",
        ]);

        $this->assertMatchesRegularExpression('~^/lots/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(Lot::class);
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
        $token = LoginTest::getAdminLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Lot::class, ['name' => 'Lot de test']);

        $client->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Lot de test 2.0',
                'quantity' => 2,
                'message' => 'C\'est un autre super lot !',
            ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'name' => 'Lot de test 2.0',
            'quantity' => 2,
            'message' => 'C\'est un autre super lot !',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testFailedDeleteLot(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Lot::class, ['name' => 'Lot de test 2.0']);

        $client->request('DELETE', $iri, ['auth_bearer' => $token]);

        // ConstraintViolation with a reward
        self::assertResponseStatusCodeSame(422);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testDeleteLot(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $client = self::createClient();
        $iri = $this->findIriBy(Lot::class, ['name' => 'Supprime moi !']);

        $client->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(204);

        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(Lot::class)->findOneBy(['name' => 'Supprime moi !'])
        );
    }
}
