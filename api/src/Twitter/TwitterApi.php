<?php

declare(strict_types=1);

namespace App\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TwitterApi
{
    public function __construct(
        private string $twitterConsumerKey,
        private string $twitterConsumerSecret,
        private string $twitterAccessToken,
        private string $twitterAccessTokenSecret
    )
    {
    }

    private function getConnection(): TwitterOAuth
    {
        return new TwitterOAuth($this->twitterConsumerKey, $this->twitterConsumerSecret, $this->twitterAccessToken, $this->twitterAccessTokenSecret);
    }

    /**
     * @throws TwitterOAuthException
     */
    public function makeAnGetTwitterApiRequest(string $url, array $params = [], string $apiVersion = '2'): array|object
    {
        $connection = $this->getConnection();
        $connection->setApiVersion($apiVersion);
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
    public function makeAnPostTwitterApiRequest(string $url, array $params = [], bool $json = true, string $apiVersion = '2'): array|object
    {
        $connection = $this->getConnection();
        $connection->setApiVersion($apiVersion);
        $response = $connection->post($url, $params, $json);

        if ($connection->getLastHttpCode() === 201) {
            return $response;
        } else {
            throw new BadRequestHttpException($response->errors[0]->message);
        }
    }
}
