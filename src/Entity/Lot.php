<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\LotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[ORM\Table(name: '`lot`')]
#[UniqueEntity('name')]
#[ApiResource(
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

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(iri: "https://schema.org/name")]
    private string $name = '';

    #[ORM\Column(name: 'quantity', type: 'integer', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[ApiProperty(iri: "https://schema.org/Quantity")]
    private int $quantity = 0;

    #[ORM\Column(name: 'message', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(description: 'Message that will be sent to players. To write the user name in the message, write : %user%', iri: "https://schema.org/Message")]
    private string $message = '';

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(name: 'image', nullable: true)]
    #[ApiProperty(iri: 'https://schema.org/image')]
    public ?MediaObject $image = null;

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

    public function getMessage(?string $username = null): ?string
    {
        if (null !== $username) {
            return str_replace("%user%", $username, $this->message);
        }
        return $this->message;
    }

    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    public function getImage(): ?MediaObject
    {
        return $this->image;
    }

    public function setImage(?MediaObject $image): self
    {
        $this->image = $image;

        return $this;
    }
}
