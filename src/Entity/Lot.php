<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\LotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[ORM\Table(name: '`lot`')]
#[UniqueEntity('name')]
#[
    ApiResource(
        collectionOperations: [
            "get",
            "post"
        ],
        itemOperations: [
            "get",
            "put",
            "patch",
            "delete"
        ],
        attributes: [
            "order" => ["name" => "ASC", "quantity" => "DESC"],
            "security" => "is_granted('ROLE_ADMIN')"
        ]
    )]
#[ApiFilter(SearchFilter::class, properties: ["name" => "ipartial"])]
class Lot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id', type: 'integer')]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private int $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    #[ApiProperty(iri: "https://schema.org/name")]
    private string $name = '';

    #[ORM\Column(name: 'quantity', type: 'integer')]
    #[ApiProperty(iri: "https://schema.org/Quantity")]
    private int $quantity = 0;

    #[ORM\Column(name: 'picture_url', type: 'string', length: 255, nullable: true)]
    #[ApiProperty(iri: "https://schema.org/URL")]
    private string $pictureUrl = '';

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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPictureUrl(): ?string
    {
        return $this->pictureUrl;
    }

    public function setPictureUrl(?string $pictureUrl): self
    {
        $this->pictureUrl = $pictureUrl;

        return $this;
    }
}
