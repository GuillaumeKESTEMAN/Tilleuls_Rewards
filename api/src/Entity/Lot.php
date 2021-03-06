<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\LotRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LotRepository::class)]
#[UniqueEntity('name')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Delete()
    ],
    order: ["name" => "ASC", "quantity" => "DESC"]
)]
#[ApiFilter(SearchFilter::class, properties: ["name" => "ipartial"])]
class Lot
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(types: ["https://schema.org/name"])]
    private ?string $name = null;

    #[ORM\Column(name: 'quantity', type: 'integer', nullable: false)]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[ApiProperty(types: ["https://schema.org/Quantity"])]
    private ?int $quantity = null;

    #[ORM\Column(name: 'message', type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(description: 'Message that will be sent to players. To write the player name in the message, write : %player_name% and same for the userhandle to mention it (%@userhandle%)', types: ["https://schema.org/Message"])]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(name: 'image', nullable: true)]
    #[ApiProperty(types: ['https://schema.org/image'])]
    public ?MediaObject $image = null;

    public function getId(): Uuid
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

    public function getMessage(?string $name = null, ?string $userhandle = null): ?string
    {
        if (null !== $name) {
            return str_replace("%player_name%", $name, $this->message);
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
