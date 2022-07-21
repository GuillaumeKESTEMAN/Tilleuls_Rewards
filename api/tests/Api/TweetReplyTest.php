<?php

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TweetReply;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TweetReplyTest extends ApiTestCase
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
        $response = static::createClient()->request('GET', '/api/tweet_replies', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        // Asserts that the returned JSON is a superset of this one
        self::assertJsonContains([
            '@context' => '/api/contexts/TweetReply',
            '@id' => '/api/tweet_replies',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);

        // Because test fixtures are automatically loaded between each test, you can assert on them
        $this->assertCount(1, $response->toArray()['hydra:member']);

        // Asserts that the returned JSON is validated by the JSON Schema generated for this resource by API Platform
        // This generated JSON Schema is also used in the OpenAPI spec!
        self::assertMatchesResourceCollectionJsonSchema(TweetReply::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetTweetReply(): void
    {
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];


        $client = static::createClient();
        $iri = $this->findIriBy(TweetReply::class, ['name' => 'on_new_game']);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/TweetReply',
            '@id' => $iri,
            '@type' => 'TweetReply',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testCreateTweetReply(): void
    {
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];


        $response = static::createClient()->request('POST', '/api/tweet_replies', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'need_to_follow_us',
                'message' => 'you need to follow test !!!'
            ]
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains([
            '@context' => '/api/contexts/TweetReply',
            '@type' => 'TweetReply',
            'name' => 'need_to_follow_us',
            'message' => 'you need to follow test !!!'
        ]);
        $this->assertMatchesRegularExpression('~^/api/tweet_replies/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(TweetReply::class);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testUpdateTweetReply(): void
    {
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];

        $client = static::createClient();
        // findIriBy allows to retrieve the IRI of an item by searching for some of its properties.
        $iri = $this->findIriBy(TweetReply::class, ['name' => 'need_to_follow_us']);

        $client->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'message' => 'you need to follow test !!!! (i forgot one more !)'
            ]
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'name' => 'need_to_follow_us',
            'message' => 'you need to follow test !!!! (i forgot one more !)'
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testDeleteTweetReply(): void
    {
        $token = static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]])->toArray()['token'];

        $client = static::createClient();
        $iri = $this->findIriBy(TweetReply::class, ['name' => 'need_to_follow_us']);


        $client->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(204);
        $this->assertNull(
        // Through the container, you can access all your services from the tests, including the ORM, the mailer, remote API clients...
            static::getContainer()->get('doctrine')->getRepository(TweetReply::class)->findOneBy(['name' => 'need_to_follow_us'])
        );
    }
}
