<?php

declare(strict_types=1);

namespace App\Controller;

use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use App\Entity\MediaObject;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use ApiPlatform\Validator\ValidatorInterface;

#[AsController]
final class CreateMediaObjectAction extends AbstractController
{
    public function __invoke(Request $request, ValidatorInterface $validator, LoggerInterface $logger): MediaObject
    {
        $uploadedFile = $request->files->get('file');

        $mediaObject = new MediaObject();
        $mediaObject->setFile($uploadedFile);
        $mediaObject->setName($request->get('name'));

        try {
            $validator->validate($mediaObject);
        } catch (ValidationException $e) {
            $logger->error($e->getMessage(), (array)$e);
            throw $e;
        }

        return $mediaObject;
    }
}
