<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;

class ImageReaderController extends AbstractController
{
    #[Route('/image/{filePath}', name: 'app_image_reader')]
    public function indexReader(MediaObject $image): BinaryFileResponse
    {
        $root = explode('/', __DIR__);
        $root = \array_slice($root, 0, -2);
        $root = implode('/', $root);
        $filename = '/public/media/'.$image->getFilePath();

        return new BinaryFileResponse($root.$filename);
    }
}
