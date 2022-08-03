<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\Put;
use App\Repository\RewardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: RewardRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Put()
    ],
    mercure: ["private" => true],
    order: ["distributed" => "ASC"]
)]
#[ApiFilter(BooleanFilter::class, properties: ["distributed" => "exact"])]
class Reward
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Lot::class, inversedBy: "rewards")]
    #[ApiProperty(writable: false)]
    private ?Lot $lot = null;

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

    public function isDistributed(): ?bool
    {
        return $this->distributed;
    }

    public function setDistributed(bool $distributed): void
    {
        $this->distributed = $distributed;
    }
}
