<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Player;
use App\Entity\Tweet;
use App\Tests\Security\LoginTest;
use DateTime;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class TweetTest extends ApiTestCase
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetCollection(): void
    {
        self::createClient()->request('GET', '/tweets');

        self::assertResponseStatusCodeSame(404);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetTweet(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(Tweet::class, ['tweetId' => '123456']);

        self::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateTweet(): void
    {
        $iri = $this->findIriBy(Player::class, ['username' => '@TestAccount']);

        self::createClient()->request('POST', '/tweets', [
                'json' => [
                    'player' => $iri,
                    'tweetId' => '123456789',
                    'creationDate' => new DateTime(),
                ]
            ]
        );

        self::assertResponseStatusCodeSame(404);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testUpdateTweet(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(Tweet::class, ['tweetId' => '123456']);

        self::createClient()->request('PUT', $iri, [
                'auth_bearer' => $token,
                'json' => [
                    'tweetId' => '1234567',
                ]
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testDeleteTweet(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(Tweet::class, ['tweetId' => '123456']);

        self::createClient()->request('DELETE', $iri, [
                'auth_bearer' => $token,
            ]
        );

        self::assertResponseStatusCodeSame(405);
    }
}
