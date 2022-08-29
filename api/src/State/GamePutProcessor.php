<?php

declare(strict_types=1);

namespace App\State;

use Abraham\TwitterOAuth\TwitterOAuthException;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Game;
use App\Twitter\TwitterApi;
use App\Visitor\MessageNormalizer\MessageNormalizer;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class GamePutProcessor implements ProcessorInterface
{
    public function __construct(private readonly PersistProcessor  $persistProcessor,
                                private readonly TwitterApi        $twitterApi,
                                private readonly string            $appEnv,
                                private readonly LoggerInterface   $logger,
                                private readonly MessageNormalizer $messageNormalizer
    )
    {
    }

    /**
     * @throws TwitterOAuthException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ('test' !== $this->appEnv && $data instanceof Game && null !== $data->score && 'PUT' === $context['operation']->getMethod()) {
            if (null === $data->reward) {
                throw new \LogicException('Reward of the game n°' . $data->getId() . ' not exists during game PUT request');
            }
            if (null === $data->reward->lot) {
                throw new \LogicException('Lot of the reward n°' . $data->reward->getId() . ' not exists during game PUT request');
            }
            if (null === $data->tweet) {
                throw new \LogicException('Tweet of the game n°' . $data->getId() . ' not exists during game PUT request');
            }
            if (null === $data->player) {
                throw new \LogicException('Player of the game n°' . $data->getId() . ' not exists during game PUT request');
            }

            try {
                $params = [
                    'nom' => $data->player->name,
                    'joueur' => $data->player->getUsername(),
                    'score' => $data->score
                ];

                $message = $this->messageNormalizer->normalizeMessage($data->reward->lot->message, $params);

                $this->twitterApi->reply($message, $data->tweet->tweetId);
            } catch (BadRequestHttpException $e) {
                $this->logger->critical(
                    'Twitter API post request (tweets) error' . $e->getMessage(),
                    [
                        'error' => $e,
                    ]
                );
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
