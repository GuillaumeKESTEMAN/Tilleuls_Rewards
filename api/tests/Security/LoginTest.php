<?php

declare(strict_types=1);

namespace App\Tests\Security;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class LoginTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     */
    public function testLogin(): void
    {
        static::createClient()->request('POST', '/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD'],
        ]]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/json');
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function testLoginFail(): void
    {
        static::createClient()->request('POST', '/login', ['json' => [
            'username' => 'invalid user',
            'password' => 'invalid password',
        ]]);

        self::assertResponseStatusCodeSame(401);
        self::assertResponseHeaderSame('content-type', 'application/json');
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public static function getLoginToken(): string
    {
        $token = static::createClient()->request('POST', '/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD'],
        ]]);

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/json');

        return $token->toArray()['token'];
    }
}
