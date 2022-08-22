<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\MediaObject;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToReadFile;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ImageReaderController extends AbstractController
{
    public function __construct(private readonly FilesystemOperator $defaultMedia, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws FilesystemException
     */
    #[Route('/image/{filePath}', name: 'app_image_reader')]
    public function indexReader(MediaObject $image): Response
    {
        $imageContent = null;

        try {
            $imageContent = $this->defaultMedia->read($image->getFilePath());
        } catch (FilesystemException | UnableToReadFile $e) {
            $this->logger->error('Error on /image/'.$image->getFilePath().' : "'.$e->getMessage().'"', (array) $e);
            throw $e;
        }

        $headers = [
            'Content-Type' => 'image/*',
        ];

        return new Response($imageContent, 200, $headers);
    }
}
