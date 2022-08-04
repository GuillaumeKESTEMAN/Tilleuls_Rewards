<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[UniqueEntity('username')]
#[UniqueEntity('twitterAccountId')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get()
    ],
    mercure: ["private" => true],
    order: ["name" => "ASC"],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ["username" => "ipartial", "name" => "ipartial"])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'username', 'lastPlayDate'])]
class Player
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    #[ApiProperty(types: ["https://schema.org/name"])]
    private ?string $name = null;

    #[ORM\Column(name: 'username', type: 'string', length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(name: 'twitter_account_id', type: 'string', length: 255, unique: true)]
    #[ApiProperty(writable: false, types: ["https://schema.org/identifier"])]
    private ?string $twitterAccountId = null;

    #[ORM\Column(name: 'last_play_date', type: 'datetime', nullable: true)]
    #[ApiProperty(writable: false, types: ["https://schema.org/DateTime"])]
    private ?\DateTime $lastPlayDate = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Tweet::class, orphanRemoval: true)]
    #[ApiProperty(readable: false, writable: false, types: ["https://schema.org/Collection"])]
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
        if (!$this->tweets->contains($tweet)) {
            $this->tweets->add($tweet);
            $tweet->setPlayer($this);
        }
    }

    public function removeTweet(Tweet $tweet): void
    {
        if (!$this->tweets->contains($tweet)) {
            $this->tweets->removeElement($tweet);
            $tweet->setPlayer(null);
        }
    }
}
