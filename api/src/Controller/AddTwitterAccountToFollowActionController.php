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
        $username = $data->getTwitterAccountUsername();
        if($username[0] === '@') {
            $username = substr($username, 1);
        }

        $user = $this->twitterApi->get('users/by/username/' . $username);

        if (property_exists($user, "errors")) {
            throw new BadRequestHttpException($user->errors[0]->detail);
        }

        //TODO persister decoration
        $data->setTwitterAccountId($user->data->id);
        $data->setTwitterAccountName($user->data->name);

        return $data;
    }

}
