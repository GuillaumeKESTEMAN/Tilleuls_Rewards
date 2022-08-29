<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\State\StatGamesCountProvider;

#[
    ApiResource(
        operations: [
            new Get(
                uriTemplate: '/stats/games/after/{id}',
                name: 'games_stats',
                provider: StatGamesCountProvider::class,
            ),
        ],
        mercure: ['private' => true],
        paginationClientItemsPerPage: true
    )
]
class Stat
{
    public int $nbrGames = 0;

    public ?\DateTime $date = null;

    public function getId(): string
    {
        return $this->date->format('Y-m-d');
    }
}
