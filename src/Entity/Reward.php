<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use App\Repository\RewardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RewardRepository::class)]
#[ORM\Table(name: '`reward`')]
#[ApiResource(
    collectionOperations: [
        "get"
    ],
    itemOperations: [
        "get",
        "put",
        "patch",
        "delete"
    ],
    attributes: [
        "order" => ["distributed" => "ASC", 'date' => "DESC"],
        "security" => "is_granted('ROLE_ADMIN')"
    ]
)]
#[ApiFilter(BooleanFilter::class, properties: ["distributed" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["date"])]
class Reward
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Lot::class)]
    #[ORM\JoinColumn(name: 'lot', nullable: true)]
    private ?Lot $lot = null;

    #[ORM\ManyToOne(targetEntity: Game::class)]
    #[ORM\JoinColumn(name: 'game', nullable: false)]
    #[ApiProperty(writable: false, iri: "https://schema.org/VideoGame")]
    private ?Game $game = null;

    #[ORM\Column(name: 'date', type: 'datetime', nullable: false)]
    #[ApiProperty(writable: false, iri: "https://schema.org/DateTime")]
    private ?\DateTime $date = null;

    #[ORM\Column(name: 'distributed', type: 'boolean', nullable: false)]
    private bool $distributed = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLot(): ?Lot
    {
        return $this->lot;
    }

    public function setLot(?Lot $lot): self
    {
        $this->lot = $lot;

        return $this;
    }

    public function getGame(): ?Game
    {
        return $this->game;
    }

    public function setGame(?Game $game): self
    {
        $this->game = $game;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isDistributed(): ?bool
    {
        return $this->distributed;
    }

    public function setDistributed(bool $distributed): self
    {
        $this->distributed = $distributed;

        return $this;
    }
}
