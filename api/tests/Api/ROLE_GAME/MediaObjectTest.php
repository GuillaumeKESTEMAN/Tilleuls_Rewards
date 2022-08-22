<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_GAME;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\MediaObject;
use App\Tests\Security\LoginTest;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class MediaObjectTest extends ApiTestCase
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

        self::createClient()->request('GET', '/media_objects', ['auth_bearer' => $token]);

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
    public function testGetMediaObject(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(MediaObject::class, ['filePath' => 'game_image.png']);

        self::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/MediaObject',
            '@id' => $iri,
            '@type' => 'https://schema.org/MediaObject',
        ]);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface|DecodingExceptionInterface
     */
    public function testCreateAMediaObject(): void
    {
        $token = LoginTest::getGameLoginToken();

        $file = new UploadedFile('fixtures/test/files/image.png', 'image.png');

        self::createClient()->request('POST', '/media_objects', [
            'auth_bearer' => $token,
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'parameters' => [
                    'name' => 'My game uploaded file',
                ],
                'files' => [
                    'file' => $file,
                ],
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
    public function testUpdateMediaObject(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(MediaObject::class, ['filePath' => 'game_image.png']);

        self::createClient()->request('PUT', $iri, [
                'auth_bearer' => $token,
                'json' => [
                    'name' => 'My uploaded file',
                ],
            ]
        );

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface|DecodingExceptionInterface
     * @throws Exception
     */
    public function testDeleteMediaObject(): void
    {
        $token = LoginTest::getGameLoginToken();

        $iri = $this->findIriBy(MediaObject::class, ['filePath' => 'game_image.png']);

        self::createClient()->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(403);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
    }
}
