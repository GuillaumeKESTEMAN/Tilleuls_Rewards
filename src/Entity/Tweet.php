<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TweetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[ORM\Table(name: '`tweet`')]
#[
    ApiResource(
        collectionOperations: [
            "get"
        ],
        iri: "https://schema.org/SocialMediaPosting",
        itemOperations: [
            "get",
            "put",
            "patch",
            "delete"
        ],
        attributes: [
            "order" => ["tweetUrl" => "ASC"],
            "security" => "is_granted('ROLE_ADMIN')"
        ]
    )]
#[ApiFilter(SearchFilter::class, properties: ["tweetUrl" => "ipartial"])]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private int $id;

    #[ORM\Column(name: 'player', type: 'integer')]
    private ?Player $player;

    #[ORM\Column(name: 'tweet_url', type: 'string', length: 255)]
    #[ApiProperty(iri: "https://schema.org/URL")]
    private string $tweetUrl = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    public function setPlayer(?Player $player): self
    {
        $this->player = $player;

        return $this;
    }

    public function getTweetUrl(): ?string
    {
        return $this->tweetUrl;
    }

    public function setTweetUrl(string $tweetUrl): self
    {
        $this->tweetUrl = $tweetUrl;

        return $this;
    }
}
