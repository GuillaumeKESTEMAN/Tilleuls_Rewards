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
        "delete"
    ],
    order: ["name" => "ASC", "quantity" => "DESC"],
    security: "is_granted('ROLE_ADMIN')"
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
    private ?string $name = null;

    #[ORM\Column(name: 'quantity', type: 'integer', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[ApiProperty(iri: "https://schema.org/Quantity")]
    private ?int $quantity = null;

    #[ORM\Column(name: 'message', type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(description: 'Message that will be sent to players. To write the username in the message, write : %username% and same for the userhandle to mention it (%@userhandle%)', iri: "https://schema.org/Message")]
    private ?string $message = null;

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

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getMessage(?string $username = null, ?string $userhandle = null): ?string
    {
        if (null !== $username) {
            return str_replace("%username%", $username, $this->message);
        }

        if (null !== $userhandle) {
            return str_replace("%@userhandle%", "@".$userhandle, $this->message);
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

    public function setImage(?MediaObject $image): void
    {
        $this->image = $image;
    }
}
