<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\PlayerRepository;
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

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    #[ApiProperty(iri: "https://schema.org/name")]
    private string $name = '';

    #[ORM\Column(name: 'twitter_account_url', type: 'string', length: 255)]
    #[ApiProperty(iri: "https://schema.org/URL")]
    private string $twitterAccountUrl = '';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTwitterAccountUrl(): ?string
    {
        return $this->twitterAccountUrl;
    }

    public function setTwitterAccountUrl(string $twitterAccountUrl): self
    {
        $this->twitterAccountUrl = $twitterAccountUrl;

        return $this;
    }
}
