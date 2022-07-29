<?php

declare(strict_types=1);

namespace App\State;

use Abraham\TwitterOAuth\TwitterOAuthException;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\TwitterAccountToFollow;
use App\Twitter\TwitterApi;
use Psr\Log\LoggerInterface;

class TwitterAccountToFollowProcessor implements ProcessorInterface
{
    public function __construct(private readonly PersistProcessor $persistProcessor, private readonly TwitterApi $twitterApi, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @param $data
     * @param Operation $operation
     * @param array $uriVariables
     * @param array $context
     * @return object
     * @throws TwitterOAuthException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ($data instanceof TwitterAccountToFollow && ($context['operation']->getMethod() === 'POST' || $context['operation']->getMethod() === 'PUT')) {
            $user = $this->twitterApi->get('users/by/username/' . substr($data->getTwitterAccountUsername(), 1));

            $data->setTwitterAccountId($user->data->id);
            $data->setTwitterAccountName($user->data->name);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
