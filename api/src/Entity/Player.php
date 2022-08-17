<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Common\Filter\OrderFilterInterface;
use ApiPlatform\Doctrine\Orm\Filter\ExistsFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PlayerRepository::class)]
#[UniqueEntity('username')]
#[UniqueEntity('twitterAccountId')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
    ],
    mercure: ['private' => true],
    order: ['lastPlayDate' => 'DESC', 'name' => 'ASC'],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['username' => 'ipartial', 'name' => 'ipartial'])]
#[ApiFilter(ExistsFilter::class, properties: ['lastPlayDate'])]
#[ApiFilter(OrderFilter::class, properties: ['lastPlayDate' => ['nulls_comparison' => OrderFilterInterface::NULLS_ALWAYS_LAST, 'default_direction' => 'DESC'], 'name', 'username'])]
class Player
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string')]
    #[Assert\NotBlank]
    #[ApiProperty(types: ['https://schema.org/name'])]
    private ?string $name = null;

    #[ORM\Column(name: 'username', type: 'string', unique: true)]
    #[Assert\Length(
        min: 1,
        max: 16,
        minMessage: 'Le pseudo demande au moins {{ limit }} caractère',
        maxMessage: 'Le pseudo ne peut pas avoir plus de {{ limit }} caractères (@ inclut)'
    )]
    #[Assert\Regex(
        pattern: '/^[@]?[A-Za-z0-9_]+$/',
        message: "Le pseudo ne doit contenir que des lettres, des chiffres et des '_' (il est possible de mettre un @ au début)"
    )]
    private ?string $username = null;

    #[ORM\Column(name: 'twitter_account_id', type: 'string', length: 255, unique: true)]
    #[Assert\Regex(
        pattern: '/^[0-9]+$/',
        message: "L'id ne doit contenir que des chiffres"
    )]
    #[ApiProperty(writable: false, types: ['https://schema.org/identifier'])]
    private ?string $twitterAccountId = null;

    #[ORM\Column(name: 'last_play_date', type: 'datetime', nullable: true)]
    #[Assert\Type(\DateTimeInterface::class)]
    #[ApiProperty(writable: false, types: ['https://schema.org/DateTime'])]
    private ?\DateTime $lastPlayDate = null;

    #[ORM\OneToMany(mappedBy: 'player', targetEntity: Tweet::class, orphanRemoval: true)]
    #[ApiProperty(readable: false, writable: false, types: ['https://schema.org/Collection'])]
    private Collection $tweets;

    public function __construct()
    {
        $this->tweets = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

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
        if ('@' !== $username[0]) {
            $username = '@'.$username;
        }

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
