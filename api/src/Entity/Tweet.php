<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\TweetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[UniqueEntity('tweetId')]
#[ApiResource(
    types: ['https://schema.org/SocialMediaPosting'],
    operations: [
        new Get(),
    ],
    mercure: ['private' => true],
    paginationClientItemsPerPage: true
)]
class Tweet
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'tweets')]
    private ?Player $player = null;

    #[ORM\Column(name: 'tweet_id', type: 'string', length: 255, unique: true)]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
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
