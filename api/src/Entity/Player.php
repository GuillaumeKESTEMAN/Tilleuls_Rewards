<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: '`player`')]
#[UniqueEntity('name')]
#[ApiResource(
    collectionOperations: [
        "get"
    ],
    itemOperations: [
        "get"
    ],
    order: ["name" => "ASC"]
)]
#[ApiFilter(SearchFilter::class, properties: ["username" => "ipartial", "name" => "ipartial"])]
class Player
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    #[ApiProperty(iri: "https://schema.org/name")]
    private ?string $name = null;

    #[ORM\Column(name: 'username', type: 'string', length: 255, nullable: false)]
    private ?string $username = null;

    #[ORM\Column(name: 'twitter_account_id', type: 'string', length: 255, nullable: false)]
    #[ApiProperty(writable: false, iri: "https://schema.org/identifier")]
    private ?string $twitterAccountId = null;

    #[ORM\Column(name: 'last_play_date', type: 'datetime', nullable: true)]
    #[ApiProperty(writable: false, iri: "https://schema.org/DateTime")]
    private ?\DateTime $lastPlayDate = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Tweet::class, orphanRemoval: true)]
    #[ApiProperty(readable: false, writable: false, iri: "https://schema.org/Collection")]
    private Collection $tweets;

    public function __construct()
    {
        $this->tweets = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getTwitterAccountId(): ?string
    {
        return $this->twitterAccountId;
    }

    public function setTwitterAccountId(string $twitterAccountId): void
    {
        $this->twitterAccountId = $twitterAccountId;
    }

    public function getLastPlayDate(): ?\DateTime
    {
        return $this->lastPlayDate;
    }

    public function setLastPlayDate(?\DateTime $lastPlayDate): void
    {
        $this->lastPlayDate = $lastPlayDate;
    }

    /**
     * @return Collection<int, Tweet>
     */
    public function getTweets(): Collection
    {
        return $this->tweets;
    }

    public function addTweet(Tweet $tweet): void
    {
        $tweet->setPlayer($this);
        if (!in_array($tweet, (array)$this->tweets)) {
            $this->tweets[] = $tweet;
        }
    }

    public function removeTweet(Tweet $tweet): void
    {
        if ($this->tweets->removeElement($tweet)) {
            if ($tweet->getPlayer() === $this) {
                $tweet->setPlayer(null);
            }
        }
    }
}
