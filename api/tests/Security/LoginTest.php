<?php

declare(strict_types=1);

namespace App\Tests\Security;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class LoginTest extends ApiTestCase
{
    /**
     * @throws TransportExceptionInterface
     */
    public function testLogin(): void
    {
        static::createClient()->request('POST', '/api/login', ['json' => [
            'username' => $_ENV['USER_IN_MEMORY_USERNAME'],
            'password' => $_ENV['USER_IN_MEMORY_PASSWORD']
        ]]);

        self::assertResponseIsSuccessful();

        self::assertResponseHeaderSame('content-type', 'application/json');
    }
}
