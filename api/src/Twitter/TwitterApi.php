<?php

declare(strict_types=1);

namespace App\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TwitterApi
{
    private array $memoize = [];

    public function __construct(
        private string $twitterConsumerKey,
        private string $twitterConsumerSecret,
        private string $twitterAccessToken,
        private string $twitterAccessTokenSecret
    )
    {
    }

    /**
     * @return TwitterOAuth
     */
    private function getConnection(): TwitterOAuth
    {
        return new TwitterOAuth($this->twitterConsumerKey, $this->twitterConsumerSecret, $this->twitterAccessToken, $this->twitterAccessTokenSecret);
    }

    /**
     * @throws TwitterOAuthException
     */
    public function get(string $url, array $params = [], string $apiVersion = '2'): array|object
    {
        $request = serialize([$url, $params]);

        if($response = $this->memoize[$request] ?? null) {
            return $response;
        }

        $connection = $this->getConnection();
        $connection->setApiVersion($apiVersion);
        $response = $connection->get($url, $params);

        if ($connection->getLastHttpCode() === 200) {
            $this->memoize[$request] = $response;
            return $response;
        }

        throw new BadRequestHttpException($response->errors[0]->message);
    }

    /**
     * @throws TwitterOAuthException
     */
    public function post(string $url, array $params = [], bool $json = true, string $apiVersion = '2'): array|object
    {
        $connection = $this->getConnection();
        $connection->setApiVersion($apiVersion);
        $response = $connection->post($url, $params, $json);

        if ($connection->getLastHttpCode() === 201) {
            return $response;
        }

        throw new BadRequestHttpException($response->errors[0]->message);
    }
}
