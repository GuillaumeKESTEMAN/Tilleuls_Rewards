<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\LotRepository;
use App\State\LotProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
        new Delete(validationContext: ['groups' => ['deleteValidation']], processor: LotProcessor::class),
    ],
    mercure: ['private' => true],
    order: ['name' => 'ASC', 'quantity' => 'DESC'],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial'])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'quantity', 'message'])]
class Lot
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[ApiProperty(types: ['https://schema.org/name'])]
    private ?string $name = null;

    #[ORM\Column(name: 'quantity', type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    #[ApiProperty(types: ['https://schema.org/Quantity'])]
    private ?int $quantity = null;

    #[ORM\Column(name: 'message', type: 'string')]
    #[Assert\NotBlank]
    #[ApiProperty(description: 'Message that will be sent to players. To write the player name in the message, write : %player_name% and same for the userhandle to mention it : %@userhandle%', types: ['https://schema.org/Message'])]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: MediaObject::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    #[ApiProperty(types: ['https://schema.org/image'])]
    public ?MediaObject $image = null;

    #[ORM\OneToMany(mappedBy: 'lot', targetEntity: Reward::class)]
    #[Assert\Count(exactly: 0, groups: ['deleteValidation'])]
    private Collection $rewards;

    public function __construct()
    {
        $this->rewards = new ArrayCollection();
    }

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
        $returnMessage = $this->message;
        if (null !== $name) {
            $returnMessage = str_replace('%player_name%', $name, $returnMessage);
        }

        if (null !== $userhandle) {
            $returnMessage = str_replace('%@userhandle%', '@'.$userhandle, $returnMessage);
        }

        return $returnMessage;
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

    public function getRewards(): Collection
    {
        return $this->rewards;
    }

    public function setRewards(Collection $rewards): void
    {
        $this->rewards = $rewards;
    }

    public function addReward(Reward $reward): void
    {
        if (!$this->rewards->contains($reward)) {
            $this->rewards->add($reward);
            $reward->setLot($this);
        }
    }

    public function removeReward(Reward $reward): void
    {
        if ($this->rewards->contains($reward)) {
            $this->rewards->removeElement($reward);
            $reward->setLot(null);
        }
    }
}
