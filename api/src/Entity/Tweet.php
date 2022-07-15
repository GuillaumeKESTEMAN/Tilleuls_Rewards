<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TweetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[ORM\Table(name: '`tweet`')]
#[ApiResource(
    collectionOperations: [],
    iri: "https://schema.org/SocialMediaPosting",
    itemOperations: ["get"],
    order: ["id" => "ASC"],
    security: "is_granted('ROLE_ADMIN')"
)]
#[ApiFilter(SearchFilter::class, properties: ["tweetId" => "partial"])]
class Tweet
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'tweets')]
    #[ORM\JoinColumn(name: 'player', nullable: false)]
    private ?Player $player = null;

    #[ORM\Column(name: 'tweet_id', type: 'string', length: 255, nullable: false)]
    #[ApiProperty(iri: "https://schema.org/identifier")]
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
