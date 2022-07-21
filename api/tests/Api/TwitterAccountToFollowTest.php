<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TwitterAccountToFollow;
use DateTime;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TwitterAccountToFollowTest extends ApiTestCase
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
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];
        // The client implements Symfony HttpClient's `HttpClientInterface`, and the response `ResponseInterface`
        $response = static::createClient()->request('GET', '/api/twitter_account_to_follows', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        self::assertJsonContains([
            '@context' => '/api/contexts/TwitterAccountToFollow',
            '@id' => '/api/twitter_account_to_follows',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(1, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
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
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];


        $client = static::createClient();
        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['twitterAccountUsername' => '@me']);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/TwitterAccountToFollow',
            '@id' => $iri,
            '@type' => 'TwitterAccountToFollow',
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
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];


        $response = static::createClient()->request('POST', '/api/twitter_account_to_follows', [
            'auth_bearer' => $token,
            'json' => [
                "twitterAccountUsername" => "@coopTilleuls",
                "active" => true
            ]
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context' => '/api/contexts/TwitterAccountToFollow',
            '@type' => 'TwitterAccountToFollow',
            'twitterAccountUsername' => '@coopTilleuls',
            'active' => true
        ]);
        $this->assertMatchesRegularExpression('~^/api/twitter_account_to_follows/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(TwitterAccountToFollow::class);
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
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];

        $invalidTwitterUsernameAccount = 'invalidTwitterUsernameAccount';

        static::createClient()->request('POST', '/api/twitter_account_to_follows', [
            'auth_bearer' => $token,
            'json' => [
                "twitterAccountUsername" => "@" . $invalidTwitterUsernameAccount,   # not exists for the moment
                "active" => false
            ]
        ]);

        self::assertResponseStatusCodeSame(400);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Error',
            '@type' => 'hydra:Error',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'The `username` query parameter value [' . $invalidTwitterUsernameAccount . '] does not match ^[A-Za-z0-9_]{1,15}$',
        ]);
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
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];

        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['twitterAccountUsername' => '@coopTilleuls']);

        $client->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'twitterAccountUsername' => '@symfony',
                'active' => false
            ]
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'twitterAccountUsername' => '@symfony',
            'active' => false
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testDeleteTwitterAccountToFollow(): void
    {
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];

        $client = static::createClient();
        $iri = $this->findIriBy(TwitterAccountToFollow::class, ['twitterAccountUsername' => '@symfony']);


        $client->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(204);
        $this->assertNull(
        // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::getContainer()->get('doctrine')->getRepository(TwitterAccountToFollow::class)->findOneBy(['twitterAccountUsername' => '@symfony'])
        );
    }
}
