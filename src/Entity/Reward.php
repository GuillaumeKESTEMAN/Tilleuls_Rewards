<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\RewardRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: RewardRepository::class)]
#[ORM\Table(name: '`reward`')]
#[
    ApiResource(
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
            "order" => ["date" => "DESC"],
            "security" => "is_granted('ROLE_ADMIN')"
        ]
    )]
#[ApiFilter(SearchFilter::class, properties: ["date" => "ipartial", "distributed" => "exact"])]
class Reward
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private int $id;

    #[ORM\Column(name: 'lot',type: 'integer', nullable: true)]
    private ?Lot $lot;

    #[ORM\Column(name: 'tweet', type: 'string', length: 255)]
    #[ApiProperty(iri: "https://schema.org/SocialMediaPosting")]
    private ?Tweet $tweet;

    #[ORM\Column(name: 'date', type: 'date')]
    #[ApiProperty(iri: "https://schema.org/DateTime")]
    private ?\DateTime $date = null;

    #[ORM\Column(name: 'distributed', type: 'boolean')]
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

    public function getTweet(): ?Tweet
    {
        return $this->tweet;
    }

    public function setTweet(?Tweet $tweet): self
    {
        $this->tweet = $tweet;

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
