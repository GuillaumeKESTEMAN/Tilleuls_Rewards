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

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[ORM\Table(name: '`player`')]
#[UniqueEntity('name')]
#[ApiResource(
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
        "order" => ["name" => "ASC"],
        "security" => "is_granted('ROLE_ADMIN')"
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ["name" => "ipartial"])]
class Player
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private int $id;

    #[ORM\Column(name: 'username', type: 'string', length: 255, nullable: false)]
    #[ApiProperty(iri: "https://schema.org/name")]
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getTwitterAccountId(): ?string
    {
        return $this->twitterAccountId;
    }

    public function setTwitterAccountId(string $twitterAccountId): self
    {
        $this->twitterAccountId = $twitterAccountId;

        return $this;
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

    public function addTweet(Tweet $tweet): self
    {
        $tweet->setPlayer($this);
        if (!in_array($tweet, (array)$this->tweets)) {
            $this->tweets[] = $tweet;
        }

        return $this;
    }

    public function removeTweet(Tweet $tweet): self
    {
        if ($this->tweets->removeElement($tweet)) {
            if ($tweet->getPlayer() === $this) {
                $tweet->setPlayer(null);
            }
        }

        return $this;
    }
}
