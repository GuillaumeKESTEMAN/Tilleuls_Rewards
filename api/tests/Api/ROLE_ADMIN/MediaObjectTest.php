<?php

declare(strict_types=1);

namespace App\Tests\Api\ROLE_ADMIN;

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
        $token = LoginTest::getAdminLoginToken();

        $response = self::createClient()->request('GET', '/media_objects', ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/MediaObject',
            '@id' => '/media_objects',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 2,
        ]);

        $this->assertCount(2, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(MediaObject::class);
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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(MediaObject::class, ['filePath' => 'image.png']);

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
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testCreateInvalidMediaObject(): void
    {
        $token = LoginTest::getAdminLoginToken();

        $file = new UploadedFile('fixtures/test/files/invalid_file.txt', 'invalid_file.txt');

        self::createClient()->request('POST', '/media_objects', [
            'auth_bearer' => $token,
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'parameters' => [
                    'name' => 'My invalid file uploaded',
                ],
                'files' => [
                    'file' => $file,
                ],
            ],
        ]);

        self::assertResponseStatusCodeSame(422);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/ConstraintViolationList',
            '@type' => 'ConstraintViolationList',
            'hydra:title' => 'An error occurred',
            'hydra:description' => 'file: The mime type of the file is invalid ("text/plain"). Allowed mime types are "image/jpeg", "image/jpg", "image/png".',
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
        $token = LoginTest::getAdminLoginToken();

        $file = new UploadedFile('fixtures/test/files/image.png', 'image.png');

        $response = self::createClient()->request('POST', '/media_objects', [
            'auth_bearer' => $token,
            'headers' => ['Content-Type' => 'multipart/form-data'],
            'extra' => [
                'parameters' => [
                    'name' => 'My uploaded file',
                ],
                'files' => [
                    'file' => $file,
                ],
            ],
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertJsonContains([
            'name' => 'My uploaded file',
        ]);

        $this->assertMatchesRegularExpression('~^/media_objects/[0-9a-fA-F]{8}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{4}\-[0-9a-fA-F]{12}$~', $response->toArray()['@id']);
        self::assertMatchesResourceItemJsonSchema(MediaObject::class);
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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(MediaObject::class, ['name' => 'My uploaded file']);

        self::createClient()->request('PUT', $iri, [
            'auth_bearer' => $token,
            'json' => [
                'name' => 'Change media object name',
            ],]);

        self::assertResponseIsSuccessful();
        self::assertJsonContains([
                '@id' => $iri,
                'name' => 'Change media object name',
            ]
        );
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
        $token = LoginTest::getAdminLoginToken();

        $iri = $this->findIriBy(MediaObject::class, ['name' => 'Change media object name']);

        self::createClient()->request('DELETE', $iri, ['auth_bearer' => $token]);

        self::assertResponseStatusCodeSame(204);
        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(MediaObject::class)->findOneBy(['name' => 'Change media object name'])
        );
    }
}
