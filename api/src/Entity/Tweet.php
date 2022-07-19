<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TweetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[ApiResource(
    types: ["https://schema.org/SocialMediaPosting"],
    operations: [
        new Get()
    ],
    order: ["id" => "ASC"]
)]
#[ApiFilter(SearchFilter::class, properties: ["tweetId" => "partial"])]
class Tweet
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'tweets')]
    #[ORM\JoinColumn(name: 'player', nullable: false)]
    private ?Player $player = null;

    #[ORM\Column(name: 'tweet_id', type: 'string', length: 255, nullable: false)]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private ?string $tweetId = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }

    public function getTweetId(): ?string
    {
        return $this->tweetId;
    }

    public function setTweetId(string $tweetId): void
    {
        $this->tweetId = $tweetId;
    }
}
