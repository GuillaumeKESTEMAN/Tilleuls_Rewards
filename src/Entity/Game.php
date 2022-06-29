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
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Tweet::class)]
    #[ORM\JoinColumn(name: 'tweet')]
    #[ApiProperty(iri: "https://schema.org/SocialMediaPosting")]
    private ?Tweet $tweet;

    #[ORM\Column(name: 'url', type: 'string', length: 255)]
    #[ApiProperty(iri: "https://schema.org/URL")]
    private string $url;

    #[ORM\Column(name: 'score', type: 'integer', nullable: true)]
    #[ApiProperty(iri: "https://schema.org/Rating")]
    private string $score;

    #[ORM\Column(name: 'creation_date', type: 'date')]
    #[ApiProperty(iri: "https://schema.org/dateCreated")]
    private ?\DateTime $creationDate;

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
