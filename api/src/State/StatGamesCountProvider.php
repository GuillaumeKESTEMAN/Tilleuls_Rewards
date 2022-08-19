<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Stat;
use App\Repository\GameRepository;
use DateTime;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\Serializer\SerializerInterface;

final class StatGamesCountProvider implements ProviderInterface
{
    public function __construct(private readonly GameRepository $gameRepository, private readonly LoggerInterface $logger, private readonly SerializerInterface $serializer)
    {
    }

    /**
     * {@inheritDoc}
     * @throws Exception
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array
    {
        return $this->serializer->denormalize($this->gameRepository->getDaysCount(new DateTime($uriVariables['id'])), Stat::class . '[]');
    }
}
