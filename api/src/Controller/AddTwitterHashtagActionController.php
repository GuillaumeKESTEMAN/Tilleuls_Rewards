<?php

namespace App\Controller;

use App\Entity\TwitterHashtag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class AddTwitterHashtagActionController extends AbstractController
{
    public function __invoke(TwitterHashtag $data): TwitterHashtag
    {
        if($data->getHashtag()[0] === '#') {
            $data->setHashtag(substr($data->getHashtag(), 1));
        }

        return $data;
    }

}
