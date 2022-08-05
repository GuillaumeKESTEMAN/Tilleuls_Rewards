<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
final class CreateMediaObjectActionController extends AbstractController
{
    public function __invoke(Request $request, ValidatorInterface $validator): MediaObject
    {
        $uploadedFile = $request->files->get('file');

        $mediaObject = new MediaObject();
        $mediaObject->setFile($uploadedFile);
        $mediaObject->setName($request->get('name'));

        return $mediaObject;
    }
}
