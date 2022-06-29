<?php

namespace App\Controller;

use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
final class CreateMediaObjectActionController extends AbstractController
{
    public function __invoke(Request $request, ValidatorInterface $validator): MediaObject
    {
        $uploadedFile = $request->files->get('file');

        $violations = $validator->validate($uploadedFile, [
                new NotBlank(),
                new Image([
                    'maxSize' => "5M",
                    'minWidth' => 100,
                    'maxWidth' => 2000,
                    'minHeight' => 100,
                    'maxHeight' => 2000,
                    'mimeTypes' => [
                        "image/jpeg",
                        "image/jpg",
                        "image/png",
                    ],
                ])]
        );

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            throw new BadRequestHttpException($violation->getMessage());
        }

        $mediaObject = new MediaObject();
        $mediaObject->setFile($uploadedFile);

        return $mediaObject;
    }
}