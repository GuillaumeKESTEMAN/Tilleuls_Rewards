<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
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
<<<<<<< HEAD
final class Stat
=======
class Stat
>>>>>>> 0caddfb... Add Stat entity to get the count of games creations by days on a period (#1)
{

    public int $nbrGames = 0;

    public ?\DateTime $date = null;

    public function getId(): string
    {
        return $this->date->format('Y-m-d');
    }
}
