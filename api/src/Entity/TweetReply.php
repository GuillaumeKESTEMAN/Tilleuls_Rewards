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
use App\Repository\TweetReplyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TweetReplyRepository::class)]
#[UniqueEntity('name')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(denormalizationContext: ['groups' => ['put']]),
        new Delete(),
    ],
    mercure: ['private' => true],
    order: ['name' => 'ASC'],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['name' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['name', 'message'])]
class TweetReply
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Choice(['on_new_game', 'game_already_generated_less_than_a_day_ago', 'need_to_follow_us', 'no_more_available_lots'])]
    #[ApiProperty(types: ['https://schema.org/name'])]
    public ?string $name = null;

    #[ORM\Column(name: 'message', type: 'string', length: 270)]
    #[Groups('put')]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 270,
        minMessage: 'Le message demande au moins {{ limit }} caractère',
        maxMessage: 'Le message ne peut pas avoir plus de {{ limit }} caractères'
    )]
    #[ApiProperty(description: 'Message that will be sent to players. To write the player name in the message, write : %nom%, same for the userhandle mention : %@username%, and same for communication website link : %site_web%', types: ['https://schema.org/Message'])]
    public ?string $message = null;

    public function getId(): Uuid
    {
        return $this->id;
    }
}
