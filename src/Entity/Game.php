<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ORM\Table(name: '`game`')]
#[
    ApiResource(
        collectionOperations: [
            "get"
        ],
        iri: "https://schema.org/VideoGame",
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
    )
]
#[ApiFilter(SearchFilter::class, properties: ["url" => "exact", "creationDate" => "ipartial"])]
class Game
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy:"CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Tweet::class)]
    #[ORM\JoinColumn(name: 'tweet', nullable: false)]
    #[ApiProperty(iri: "https://schema.org/SocialMediaPosting")]
    private ?Tweet $tweet = null;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(name: 'player', nullable: false)]
    private ?Player $player = null;

    #[ORM\Column(name: 'url', type: 'string', length: 255, nullable: false)]
    #[ApiProperty(iri: "https://schema.org/URL")]
    private string $url = '';

    #[ORM\Column(name: 'score', type: 'integer', nullable: true)]
    #[ApiProperty(iri: "https://schema.org/Rating")]
    private ?int $score = null;

    #[ORM\Column(name: 'creation_date', type: 'datetime', nullable: false)]
    #[ApiProperty(iri: "https://schema.org/dateCreated")]
    private ?\DateTime $creationDate = null;

    public function getId(): ?Uuid
    {
        return $this->id;
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

    /**
     * @return Player|null
     */
    public function getPlayer(): ?Player
    {
        return $this->player;
    }

    /**
     * @param Player|null $player
     */
    public function setPlayer(?Player $player): void
    {
        $this->player = $player;
    }



    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): self
    {
        $this->score = $score;

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
