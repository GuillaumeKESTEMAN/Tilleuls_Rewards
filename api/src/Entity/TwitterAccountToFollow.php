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
use App\Validator as AcmeAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TwitterAccountToFollowRepository::class)]
#[UniqueEntity('twitterAccountId')]
#[UniqueEntity('twitterAccountUsername')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(processor: TwitterAccountToFollowProcessor::class),
        new Get(),
        new Put(processor: TwitterAccountToFollowProcessor::class),
        new Delete(),
    ],
    mercure: ['private' => true],
    order: ['active' => 'DESC', 'twitterAccountName' => 'ASC'],
    paginationClientItemsPerPage: true
)]
#[ApiFilter(SearchFilter::class, properties: ['twitterAccountName' => 'ipartial', 'twitterAccountUsername' => 'ipartial'])]
#[ApiFilter(BooleanFilter::class, properties: ['active' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['twitterAccountName', 'twitterAccountUsername', 'active'])]
class TwitterAccountToFollow
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\Column(name: 'twitter_account_name', type: 'string', length: 255)]
    #[ApiProperty(writable: false, types: ['https://schema.org/name'])]
    private ?string $twitterAccountName = null;

    #[ORM\Column(name: 'twitter_account_username', type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[AcmeAssert\ExistsInTwitter]
    private ?string $twitterAccountUsername = null;

    #[ORM\Column(name: 'twitter_account_id', type: 'string', length: 255, unique: true)]
    #[ApiProperty(writable: false, types: ['https://schema.org/identifier'])]
    private ?string $twitterAccountId = null;

    #[ORM\Column(name: 'active', type: 'boolean')]
    private bool $active = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTwitterAccountName(): ?string
    {
        return $this->twitterAccountName;
    }

    public function setTwitterAccountName(?string $twitterAccountName): void
    {
        $this->twitterAccountName = $twitterAccountName;
    }

    public function getTwitterAccountUsername(): ?string
    {
        return $this->twitterAccountUsername;
    }

    public function setTwitterAccountUsername(?string $twitterAccountUsername): void
    {
        $twitterAccountUsername = str_replace(' ', '', $twitterAccountUsername);
        if ('@' !== $twitterAccountUsername[0]) {
            $twitterAccountUsername = '@'.$twitterAccountUsername;
        }

        $this->twitterAccountUsername = $twitterAccountUsername;
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
