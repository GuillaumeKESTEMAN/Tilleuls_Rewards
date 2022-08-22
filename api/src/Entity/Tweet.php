<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\Repository\TweetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TweetRepository::class)]
#[UniqueEntity('tweetId')]
#[ApiResource(
    types: ['https://schema.org/SocialMediaPosting'],
    operations: [
        new Get(),
    ],
    mercure: ['private' => true],
    paginationClientItemsPerPage: true
)]
class Tweet
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\ManyToOne(targetEntity: Player::class, inversedBy: 'tweets')]
    public ?Player $player = null;

    #[ORM\Column(name: 'tweet_id', type: 'string', length: 255, unique: true)]
    #[Assert\Regex(
        pattern: '/^[0-9]+$/',
        message: "L'id ne doit contenir que des chiffres"
    )]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    public ?string $tweetId = null;

    #[ORM\Column(name: 'creation_date', type: 'datetime')]
    #[Assert\Type(\DateTimeInterface::class)]
    #[ApiProperty(types: ['https://schema.org/DateTime'])]
    public ?\DateTime $creationDate = null;

    public function getId(): Uuid
    {
        return $this->id;
    }
}
