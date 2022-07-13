<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AddTwitterHashtagActionController;
use App\Repository\TwitterHashtagRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TwitterHashtagRepository::class)]
#[ORM\Table(name: '`twitter_hashtag`')]
#[UniqueEntity('hashtag')]
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => [
            "controller" => AddTwitterHashtagActionController::class,
        ]
    ],
    itemOperations: [
        "get",
        "put" => [
            "controller" => AddTwitterHashtagActionController::class,
        ],
        "delete"
    ],
    order: ["active" => "DESC"],
    security: "is_granted('ROLE_ADMIN')"
)]
#[ApiFilter(SearchFilter::class, properties: ["hashtag" => "ipartial"])]
#[ApiFilter(BooleanFilter::class, properties: ["active" => "exact"])]
class TwitterHashtag
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(iri: "https://schema.org/identifier")]
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
