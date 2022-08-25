<?php

declare(strict_types=1);

namespace App\State;

use Abraham\TwitterOAuth\TwitterOAuthException;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\TwitterAccountToFollow;
use App\Twitter\TwitterApi;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class TwitterAccountToFollowProcessor implements ProcessorInterface
{
    public function __construct(private readonly PersistProcessor $persistProcessor, private readonly TwitterApi $twitterApi, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws TwitterOAuthException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ($data instanceof TwitterAccountToFollow && ('POST' === $context['operation']->getMethod() || 'PUT' === $context['operation']->getMethod())) {
            try {
                $user = $this->twitterApi->getUserByUsername(substr($data->getUsername(), 1));

                $data->twitterAccountId = $user->data->id;
                $data->name = $user->data->name;
                $data->setUsername($user->data->username);
            } catch (BadRequestHttpException $e) {
                $this->logger->critical(
                    'Twitter API get request (users/by/username) error : '.$e->getMessage(),
                    [
                        'error' => $e,
                    ]
                );
                throw $e;
            }
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
