<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Repository\RewardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RewardRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Put(denormalizationContext: ['groups' => ['put']]),
    ],
    mercure: ['private' => true],
    order: ['distributed' => 'ASC', 'game.playDate' => 'ASC'],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(BooleanFilter::class, properties: ['distributed' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['distributed', 'lot.name', 'game.playDate', 'game.player.username'])]
class Reward
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Lot::class, inversedBy: 'rewards')]
    #[ApiProperty(writable: false)]
    private ?Lot $lot = null;

    #[ORM\OneToOne(mappedBy: 'reward', targetEntity: Game::class)]
    #[ApiProperty(writable: false)]
    private ?Game $game = null;

    #[ORM\Column(name: 'distributed', type: 'boolean')]
    #[Groups('put')]
    private bool $distributed = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): void
    {
        $this->lot = $lot;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): void
    {
        $this->game = $game;
    }

    public function isDistributed(): ?bool
    {
        return $this->distributed;
    }

    public function setDistributed(bool $distributed): void
    {
        $this->distributed = $distributed;
    }
}
