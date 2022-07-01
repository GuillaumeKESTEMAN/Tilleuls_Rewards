<?php

namespace App;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use stdClass;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TwitterApiService
{
    /**
     * @throws TwitterOAuthException
     */
    public static function makeAnGetTwitterApiRequest(string $url, array $params = []): ?stdClass
    {
        $connection = new TwitterOAuth($_ENV["TWITTER_CONSUMER_KEY"], $_ENV["TWITTER_CONSUMER_SECRET"], $_ENV["TWITTER_ACCESS_TOKEN"], $_ENV["TWITTER_ACCESS_TOKEN_SECRET"]);
        $connection->setApiVersion('2');
        $response = $connection->get($url, $params);

        if ($connection->getLastHttpCode() === 200) {
            return $response;
        } else {
            throw new BadRequestHttpException($response->errors[0]->message);
        }
    }

    /**
     * @throws TwitterOAuthException
     */
    public static function makeAnPostTwitterApiRequest(string $url, array $params = [], bool $json = true): ?stdClass
    {
        $connection = new TwitterOAuth($_ENV["TWITTER_CONSUMER_KEY"], $_ENV["TWITTER_CONSUMER_SECRET"], $_ENV["TWITTER_ACCESS_TOKEN"], $_ENV["TWITTER_ACCESS_TOKEN_SECRET"]);
        $connection->setApiVersion('2');
        $response = $connection->post($url, $params, $json);

        if ($connection->getLastHttpCode() === 200) {
            return $response;
        } else {
            throw new BadRequestHttpException($response->errors[0]->message);
        }
    }
}