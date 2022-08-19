<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Player;
use App\Entity\Tweet;
use App\Tests\Security\LoginTest;
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
        $response = static::createClient()->request('GET', '/tweets');

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
        $token = LoginTest::getLoginToken();

        $client = static::createClient();
        $iri = $this->findIriBy(Tweet::class, ['tweetId' => '123456']);

        $client->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Tweet',
            '@id' => $iri,
            '@type' => 'https://schema.org/SocialMediaPosting',
        ]);
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

        $response = static::createClient()->request('POST', '/tweets', ['json' => [
            'player' => $iri,
            'tweetId' => '123456789',
            'creationDate' => new \DateTime(),
        ]]);

        self::assertResponseStatusCodeSame(404);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testUpdateTweet(): void
    {
        $client = static::createClient();

        $iri = $this->findIriBy(Tweet::class, ['tweetId' => '123456']);

        $client->request('PUT', $iri, ['json' => [
            'tweetId' => '1234567',
        ]]);

        self::assertResponseStatusCodeSame(405);
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testDeleteTweet(): void
    {
        $client = static::createClient();
        $iri = $this->findIriBy(Tweet::class, ['tweetId' => '123456']);

        $client->request('DELETE', $iri);

        self::assertResponseStatusCodeSame(405);
    }
}
