<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use App\Repository\RewardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RewardRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    order: ["distributed" => "ASC", 'winDate' => "DESC"]
)]
#[ApiFilter(BooleanFilter::class, properties: ["distributed" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["winDate"])]
class Reward
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Lot::class)]
    #[ORM\JoinColumn(name: 'lot', nullable: true)]
    private ?Lot $lot = null;

    #[ORM\ManyToOne(targetEntity: Game::class)]
    #[ORM\JoinColumn(name: 'game', nullable: false)]
    #[ApiProperty(writable: false, types: ["https://schema.org/VideoGame"])]
    private ?Game $game = null;

    #[ORM\Column(name: 'win_date', type: 'datetime', nullable: false)]
    #[ApiProperty(writable: false, types: ["https://schema.org/DateTime"])]
    private ?\DateTime $winDate = null;

    #[ORM\Column(name: 'distributed', type: 'boolean', nullable: false)]
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

    public function getWinDate(): ?\DateTimeInterface
    {
        return $this->winDate;
    }

    public function setWinDate(?\DateTimeInterface $winDate): void
    {
        $this->winDate = $winDate;
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
