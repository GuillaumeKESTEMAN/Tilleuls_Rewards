<?php

declare(strict_types=1);

namespace App\DataFixtures\Processor;

use App\Entity\MediaObject;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Filesystem\Filesystem;

final class MediaObjectProcessor implements ProcessorInterface
{
    public function __construct(private readonly string $appEnv, private readonly string $kernelDir)
    {
    }

    /**
     * {@inheritdoc}
     */
    public
    function preProcess(string $id, $object): void
    {
        if (!$object instanceof MediaObject) {
            return;
        }

        if ('test' !== $this->appEnv) {
            return;
        }

        $fs = new Filesystem();

        $fs->touch($this->kernelDir . '/fixtures/test/files/invalid_file.txt');
        $fs->appendToFile($this->kernelDir . '/fixtures/test/files/invalid_file.txt', 'My invalid file !!!');
    }

    /**
     * {@inheritdoc}
     */
    public
    function postProcess(string $id, $object): void
    {
        // do nothing
    }
}
