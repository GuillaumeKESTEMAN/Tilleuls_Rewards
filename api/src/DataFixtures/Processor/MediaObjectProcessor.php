<?php

declare(strict_types=1);

namespace App\DataFixtures\Processor;

use App\Entity\MediaObject;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Filesystem\Filesystem;

final class MediaObjectProcessor implements ProcessorInterface
{
    public function __construct(private readonly string $appEnv)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function preProcess(string $fixtureId, $object): void
    {
        if (!$object instanceof MediaObject) {
            return;
        }

        if ('test' !== $this->appEnv) {
            return;
        }

        $fs = new Filesystem();

        $root = explode('/', __DIR__);
        $root = \array_slice($root, 0, -3);
        $root = implode('/', $root);

        $fs->touch($root . '/fixtures/test/files/invalid_file.txt');
        $fs->appendToFile($root . '/fixtures/test/files/invalid_file.txt', 'My invalid file !!!');
    }

    /**
     * {@inheritdoc}
     */
    public function postProcess(string $fixtureId, $object): void
    {
        // do nothing
    }
}
