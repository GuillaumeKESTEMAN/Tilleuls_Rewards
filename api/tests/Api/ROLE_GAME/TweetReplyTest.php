<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

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
        $token = LoginTest::getGameLoginToken();

        self::createClient()->request('GET', '/tweet_replies', ['auth_bearer' => $token]);

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
    public function testGetTweetReply(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TweetReply::class, ['name' => 'no_more_available_lots']);

        self::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
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
        $token = LoginTest::getGameLoginToken();

        self::createClient()->request('POST', '/tweet_replies', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'invalid_name',
                'message' => 'valid message',
            ],
        ]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
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
        $token = LoginTest::getGameLoginToken();

        self::createClient()->request('POST', '/tweet_replies', [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'game_already_generated_less_than_a_day_ago',
                'message' => 'my second game test !',
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
    public function testUpdateTweetReply(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TweetReply::class, ['name' => 'no_more_available_lots']);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'message' => 'my game test !',
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
    public function testDeleteTweetReply(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(TweetReply::class, ['name' => 'no_more_available_lots']);

        self::createClient()->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
