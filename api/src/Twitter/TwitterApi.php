<?php

declare(strict_types=1);

namespace App\Twitter;

use Abraham\TwitterOAuth\TwitterOAuth;
use Abraham\TwitterOAuth\TwitterOAuthException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TwitterApi
{
    private array $memoize = [];

    public function __construct(
        private readonly string $twitterConsumerKey,
        private readonly string $twitterConsumerSecret,
        private readonly string $twitterAccessToken,
        private readonly string $twitterAccessTokenSecret,
        private readonly LoggerInterface $logger
    ) {
    }

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

        if ($response = $this->memoize[$request] ?? null) {
            return $response;
        }

        $connection = $this->getConnection();
        $connection->setApiVersion($apiVersion);
        $response = $connection->get($url, $params);

        if (200 === $connection->getLastHttpCode()) {
            $this->memoize[$request] = $response;

            return $response;
        }

        $this->logger->critical(
            'Twitter API get request error : '.$response->detail,
            [
                'response' => $response,
            ]
        );
        throw new BadRequestHttpException($response->detail);
    }

    /**
     * @throws TwitterOAuthException
     */
    public function post(string $url, array $params = [], bool $json = true, string $apiVersion = '2'): array|object
    {
        $connection = $this->getConnection();
        $connection->setApiVersion($apiVersion);
        $response = $connection->post($url, $params, $json);

        if (201 === $connection->getLastHttpCode()) {
            return $response;
        }

        $this->logger->critical(
            'Twitter API post request error : '.$response->detail,
            [
                'response' => $response,
            ]
        );

        throw new BadRequestHttpException($response->detail);
    }

    /**
     * @throws TwitterOAuthException
     */
    public function reply(string $message, string $tweetId): array|object
    {
        $params = [
            'text' => $message,
            'reply' => [
                'in_reply_to_tweet_id' => $tweetId,
            ],
        ];

        return $this->post('tweets', $params);
    }
}
