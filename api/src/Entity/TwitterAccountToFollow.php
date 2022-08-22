<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
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
use App\Repository\TwitterAccountToFollowRepository;
use App\State\TwitterAccountToFollowProcessor;
use App\Validator\ExistsInTwitter;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[ORM\Entity(repositoryClass: TwitterAccountToFollowRepository::class)]
#[UniqueEntity('twitterAccountId')]
#[UniqueEntity('username')]
#[ApiResource(
    operations: [
        new GetCollection(security: 'is_granted("ROLE_ADMIN") || is_granted("ROLE_GAME")'),
        new Post(validationContext: ['groups' => new GroupSequence(['firstPostValidation', 'secondPostValidation'])], processor: TwitterAccountToFollowProcessor::class),
        new Get(),
        new Put(denormalizationContext: ['groups' => ['put']], processor: TwitterAccountToFollowProcessor::class),
        new Delete(),
    ],
    mercure: ['private' => true],
    order: ['active' => 'DESC', 'name' => 'ASC'],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'ipartial', 'username' => 'ipartial'])]
#[ApiFilter(BooleanFilter::class, properties: ['active' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'username', 'active'])]

class TwitterAccountToFollow
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255)]
    #[ApiProperty(writable: false, types: ['https://schema.org/name'])]
    private ?string $name = null;

    #[ORM\Column(name: 'username', type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank(groups: ['firstPostValidation'])]
    #[Assert\Length(
        min: 1,
        max: 16,
        minMessage: 'Le pseudo demande au moins {{ limit }} caractère',
        maxMessage: 'Le pseudo ne peut pas avoir plus de {{ limit }} caractères (@ inclut)',
        groups: ['firstPostValidation']
    )]
    #[Assert\Regex(
        pattern: '/^[@]?[A-Za-z0-9_]+$/',
        message: "Le pseudo ne doit contenir que des lettres, des chiffres et des '_' (il est possible de mettre un @ au début)",
        groups: ['firstPostValidation']
    )]
    #[ExistsInTwitter(groups: ['secondPostValidation'])]
    private ?string $username = null;

    #[ORM\Column(name: 'twitter_account_id', type: 'string', length: 255, unique: true)]
    #[Assert\Regex(
        pattern: '/^[0-9]+$/',
        message: "L'id ne doit contenir que des chiffres"
    )]
    #[ApiProperty(writable: false, types: ['https://schema.org/identifier'])]
    private ?string $twitterAccountId = null;

    #[ORM\Column(name: 'active', type: 'boolean')]
    #[Groups('put')]
    private bool $active = false;

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

    public function setUsername(?string $username): void
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

    public function setTwitterAccountId(?string $twitterAccountId): void
    {
        $this->twitterAccountId = $twitterAccountId;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
