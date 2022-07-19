<?php

declare(strict_types=1);

namespace App\State;

use Abraham\TwitterOAuth\TwitterOAuthException;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\TwitterAccountToFollow;
use ApiPlatform\State\ProcessorInterface;
use App\Twitter\TwitterApi;

class TwitterAccountToFollowProcessor implements ProcessorInterface
{
    public function __construct(private DataPersisterInterface $decorated, private TwitterApi $twitterApi)
    {
    }

    /**
     * {@inheritDoc}
     * @throws TwitterOAuthException
     */
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $user = $this->twitterApi->get('users/by/username/' . substr($data->getTwitterAccountUsername(), 1));

        $data->setTwitterAccountId($user->data->id);
        $data->setTwitterAccountName($user->data->name);

        return $this->decorated->persist($data, $context);
    }
}
