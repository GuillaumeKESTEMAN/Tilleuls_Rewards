<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[
    ApiResource(
        types: ["https://schema.org/VideoGame"],
        operations: [
            new GetCollection(),
            new Get(),
        ],
        order: ["creationDate" => "DESC"]
    )
]
#[ApiFilter(SearchFilter::class, properties: ["url" => "partial"])]
#[ApiFilter(DateFilter::class, properties: ["creationDate"])]
class Game
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\OneToOne(targetEntity: Tweet::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'tweet', nullable: false)]
    #[ApiProperty(types: ["https://schema.org/SocialMediaPosting"])]
    private ?Tweet $tweet = null;

    #[ORM\ManyToOne(targetEntity: Player::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'player', nullable: false)]
    private ?Player $player = null;

    #[ORM\Column(name: 'score', type: 'integer', nullable: true)]
    #[ApiProperty(types: ["https://schema.org/Rating"])]
    private ?int $score = null;

    #[ORM\Column(name: 'creation_date', type: 'datetime', nullable: false)]
    #[ApiProperty(types: ["https://schema.org/dateCreated"])]
    private ?\DateTime $creationDate = null;

    #[ORM\Column(name: 'play_date', type: 'datetime', nullable: true)]
    #[ApiProperty(types: ["https://schema.org/DateTime"])]
    private ?\DateTime $playDate = null;

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTweet(): ?Tweet
    {
        return $this->tweet;
    }

    public function setTweet(?Tweet $tweet): void
    {
        $this->tweet = $tweet;
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

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(?int $score): void
    {
        $this->score = $score;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTime $creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    public function getPlayDate(): ?\DateTime
    {
        return $this->playDate;
    }

    public function setPlayDate(?\DateTime $playDate): void
    {
        $this->playDate = $playDate;
    }
}
