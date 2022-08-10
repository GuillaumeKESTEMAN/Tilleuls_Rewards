<?php

declare(strict_types=1);

namespace App\State;

use Abraham\TwitterOAuth\TwitterOAuthException;
use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\TwitterAccountToFollow;
use App\Twitter\TwitterApi;

class TwitterAccountToFollowProcessor implements ProcessorInterface
{
    public function __construct(private readonly PersistProcessor $persistProcessor, private readonly TwitterApi $twitterApi)
    {
    }

    /**
     * @param $data
     *
     * @throws TwitterOAuthException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        if ($data instanceof TwitterAccountToFollow && ('POST' === $context['operation']->getMethod() || 'PUT' === $context['operation']->getMethod())) {
            $user = $this->twitterApi->get('users/by/username/'.substr($data->getUsername(), 1));

            $data->setTwitterAccountId($user->data->id);
            $data->setName($user->data->name);
            $data->setUsername($user->data->username);
        }

        return $this->persistProcessor->process($data, $operation, $uriVariables, $context);
    }
}
