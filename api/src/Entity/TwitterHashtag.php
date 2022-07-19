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
use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TwitterHashtagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TwitterHashtagRepository::class)]
#[UniqueEntity('hashtag')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Delete()
    ],
    order: ["active" => "DESC"]
)]
#[ApiFilter(SearchFilter::class, properties: ["hashtag" => "ipartial"])]
#[ApiFilter(BooleanFilter::class, properties: ["active" => "exact"])]
class TwitterHashtag
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\Column(name: 'hashtag', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    private ?string $hashtag = null;

    #[ORM\Column(name: 'active', type: 'boolean', nullable: false)]
    private bool $active = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getHashtag(): ?string
    {
        return $this->hashtag;
    }

    /**
     * @param string|null $hashtag
     */
    public function setHashtag(?string $hashtag): void
    {
        $hashtag = str_replace(' ', '', $hashtag);
        if($hashtag[0] !== '#') {
            $hashtag = '#' . $hashtag;
        }

        $this->hashtag = $hashtag;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }
}
