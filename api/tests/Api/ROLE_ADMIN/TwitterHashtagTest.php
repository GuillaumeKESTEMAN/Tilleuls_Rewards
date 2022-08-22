<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_ADMIN;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\TwitterHashtag;
use App\Tests\Security\LoginTest;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class TwitterHashtagTest extends ApiTestCase
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

        $response = self::createClient()->request('GET', '/twitter_hashtags', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TwitterHashtag',
            '@id' => '/twitter_hashtags',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 60,
            'hydra:view' => [
                '@id' => '/twitter_hashtags?page=1',
                '@type' => 'hydra:PartialCollectionView',
                'hydra:first' => '/twitter_hashtags?page=1',
                'hydra:last' => '/twitter_hashtags?page=3',
                'hydra:next' => '/twitter_hashtags?page=2',
            ],
        ]);

        $this->assertCount(20, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(TwitterHashtag::class);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetTwitterHashtag(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(TwitterHashtag::class, ['hashtag' => '#getTest']);

        self::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TwitterHashtag',
            '@id' => $iri,
            '@type' => 'TwitterHashtag',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testCreateTwitterHashtag(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $response = self::createClient()->request('POST', '/twitter_hashtags', [
            'auth_bearer' => $token,
            'json' => [
                'hashtag' => '#test',
                'active' => false,
            ],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/TwitterHashtag',
            '@type' => 'TwitterHashtag',
            'hashtag' => '#test',
            'active' => false,
        ]);

        $this->assertMatchesRegularExpression('~^/twitter_hashtags/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(TwitterHashtag::class);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     */
    public function testUpdateTwitterHashtag(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(TwitterHashtag::class, ['hashtag' => '#getTest']);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'hashtag' => '#getTest2.0',
                'active' => true,
            ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
            '@id' => $iri,
            'hashtag' => '#getTest',
            'active' => true,
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws Exception
     * @throws DecodingExceptionInterface
     */
    public function testDeleteTwitterHashtag(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(TwitterHashtag::class, ['hashtag' => '#getTest']);

        self::createClient()->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(204);
        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(TwitterHashtag::class)->findOneBy(['hashtag' => '#getTest'])
        );
    }
}
