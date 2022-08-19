<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Game;
use App\Tests\Security\LoginTest;
use DateTime;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

<<<<<<< HEAD
final class StatTest extends ApiTestCase
=======
class StatTest extends ApiTestCase
>>>>>>> 0caddfb... Add Stat entity to get the count of games creations by days on a period (#1)
{
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testGetStatGame(): void
    {
        $token = LoginTest::getLoginToken();

        $iri = '/stats/games/after/'.date('Y-m-d', strtotime('-30 days'));

        static::createClient()->request('GET', $iri, ['auth_bearer' => $token]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/contexts/Stat',
            '@id' => $iri,
        ]);
    }
}
