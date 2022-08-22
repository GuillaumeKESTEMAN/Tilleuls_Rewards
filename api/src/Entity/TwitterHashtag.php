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
use App\Repository\TwitterHashtagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TwitterHashtagRepository::class)]
#[UniqueEntity('hashtag')]
#[ApiResource(
    operations: [
        new GetCollection(security: 'is_granted("ROLE_ADMIN") || is_granted("ROLE_GAME")'),
        new Post(),
        new Get(),
        new Put(denormalizationContext: ['groups' => ['put']]),
        new Delete(),
    ],
    mercure: ['private' => true],
    order: ['active' => 'DESC', 'hashtag' => 'ASC'],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['hashtag' => 'ipartial'])]
#[ApiFilter(BooleanFilter::class, properties: ['active' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['hashtag', 'active'])]
class TwitterHashtag
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\Column(name: 'hashtag', type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 30,
        minMessage: 'Le hashtag demande au moins {{ limit }} caractère',
        maxMessage: 'Le hashtag ne peut pas avoir plus de {{ limit }} caractères (# inclut)'
    )]
    private ?string $hashtag = null;

    #[ORM\Column(name: 'active', type: 'boolean')]
    #[Groups('put')]
    public bool $active = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getHashtag(): ?string
    {
        return $this->hashtag;
    }

    public function setHashtag(?string $hashtag): void
    {
        $hashtag = str_replace(' ', '_', $hashtag);
        if ('#' !== $hashtag[0]) {
            $hashtag = '#'.$hashtag;
        }

        $this->hashtag = $hashtag;
    }
}
