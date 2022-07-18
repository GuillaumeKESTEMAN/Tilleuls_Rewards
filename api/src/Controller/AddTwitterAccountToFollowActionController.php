<?php

namespace App\Controller;

use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Entity\TwitterAccountToFollow;
use App\Twitter\TwitterApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class AddTwitterAccountToFollowActionController extends AbstractController
{
    public function __construct(private TwitterApi $twitterApi)
    {
    }

    /**
     * @throws TwitterOAuthException
     */
    public function __invoke(TwitterAccountToFollow $data): TwitterAccountToFollow
    {
        //TODO custom constraint
        if($data->getTwitterAccountUsername()[0] === '@') {
            $data->setTwitterAccountUsername(substr($data->getTwitterAccountUsername(), 1));
        }

        $user = $this->twitterApi->get('users/by/username/' . $data->getTwitterAccountUsername());

        if (property_exists($user, "errors")) {
            throw new BadRequestHttpException($user->errors[0]->detail);
        }

        //TODO persister decoration
        $data->setTwitterAccountId($user->data->id);
        $data->setTwitterAccountName($user->data->name);

        return $data;
    }

}
