<?php

declare(strict_types=1);

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class TwitterApiRecentTweetsCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:get-recent-tweets');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--update-db',
        ]);

        $commandTester->assertCommandIsSuccessful();
    }
}
