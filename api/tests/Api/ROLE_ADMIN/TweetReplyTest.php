<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_ADMIN;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TweetReply;
use App\Tests\Security\LoginTest;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class TweetReplyTest extends ApiTestCase
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

        $response = self::createClient()->request('GET', '/tweet_replies', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TweetReply',
            '@id' => '/tweet_replies',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);

        $this->assertCount(2, $response->toArray()['hydra:member']);

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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(TweetReply::class, ['name' => 'on_new_game']);

        self::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TweetReply',
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
    public function testCreateInvalidTweetReply(): void
    {
        $token = LoginTest::getAdminLoginToken();

        self::createClient()->request('POST', '/tweet_replies', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'invalid_name',
                'message' => 'valid message',
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'name: The value you selected is not a valid choice.',
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
        $token = LoginTest::getAdminLoginToken();

        $response = self::createClient()->request('POST', '/tweet_replies', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'need_to_follow_us',
                'message' => 'you need to follow test !!!',
            ],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TweetReply',
            '@type' => 'TweetReply',
            'name' => 'need_to_follow_us',
            'message' => 'you need to follow test !!!',
        ]);

        $this->assertMatchesRegularExpression('~^/tweet_replies/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(TweetReply::class, ['name' => 'need_to_follow_us']);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'message' => 'you need to follow test !!!! (i forgot one more !)',
            ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'name' => 'need_to_follow_us',
            'message' => 'you need to follow test !!!! (i forgot one more !)',
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testDeleteTweetReply(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(TweetReply::class, ['name' => 'need_to_follow_us']);

        self::createClient()->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(204);

        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(TweetReply::class)->findOneBy(['name' => 'need_to_follow_us'])
        );
    }
}
