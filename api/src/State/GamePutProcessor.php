<?php

declare(strict_types=1);

namespace App\State;

use Abraham\TwitterOAuth\TwitterOAuthException;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Game;
use App\Twitter\TwitterApi;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class GamePutProcessor implements ProcessorInterface
{
    public function __construct(private readonly PersistProcessor $persistProcessor, private readonly TwitterApi $twitterApi, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @param $data
     *
     * @throws TwitterOAuthException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ('test' !== $_ENV['APP_ENV'] && $data instanceof Game && 'PUT' === $context['operation']->getMethod() && null !== $data->getScore()) {
            if (null === $data->getReward()) {
                throw new \LogicException('Reward of the game n°'.$data->getId().' not exists during game PUT request');
            }
            if (null === $data->getReward()->getLot()) {
                throw new \LogicException('Lot of the reward n°'.$data->getReward()->getId().' not exists during game PUT request');
            }
            if (null === $data->getTweet()) {
                throw new \LogicException('Tweet of the game n°'.$data->getId().' not exists during game PUT request');
            }

            try {
                $this->twitterApi->reply($data->getReward()->getLot()->getMessage(), $data->getTweet()->getTweetId());
            } catch (BadRequestHttpException $e) {
                $this->logger->critical(
                    'Twitter API post request (tweets) error'.$e->getMessage(),
                    [
                        'error' => $e,
                    ]
                );
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
