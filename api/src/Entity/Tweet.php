<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TweetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[ORM\Table(name: '`tweet`')]
#[ApiResource(
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
        "order" => ["creationDate" => "DESC"],
        "security" => "is_granted('ROLE_ADMIN')"
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ["tweetId" => "exact"])]
#[ApiFilter(DateFilter::class, properties: ["creationDate"])]
class Tweet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'tweets')]
    #[ORM\JoinColumn(name: 'player', nullable: false)]
    private ?Player $player = null;

    #[ORM\Column(name: 'tweet_id', type: 'string', length: 255, nullable: false)]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private ?string $tweetId = null;

    #[ORM\Column(name: 'creation_date', type: 'datetime', nullable: false)]
    #[ApiProperty(iri: "https://schema.org/dateCreated")]
    private ?\DateTime $creationDate = null;

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

    public function getTweetId(): ?string
    {
        return $this->tweetId;
    }

    public function setTweetId(string $tweetId): self
    {
        $this->tweetId = $tweetId;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTime $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }
}
