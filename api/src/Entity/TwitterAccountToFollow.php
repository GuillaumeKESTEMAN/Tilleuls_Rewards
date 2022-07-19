<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\AddTwitterAccountToFollowActionController;
use App\Repository\TwitterAccountToFollowRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TwitterAccountToFollowRepository::class)]
#[ORM\Table(name: '`twitter_account_to_follow`')]
#[UniqueEntity('twitterAccountId')]
#[UniqueEntity('twitterAccountName')]
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => [
            'controller' => AddTwitterAccountToFollowActionController::class
        ]
    ],
    itemOperations: [
        "get",
        "put" => [
            'controller' => AddTwitterAccountToFollowActionController::class
        ],
        "delete"
    ],
    order: ["active" => "DESC", "twitterAccountName" => "ASC"]
)]
#[ApiFilter(SearchFilter::class, properties: ["twitterAccountName" => "ipartial", "twitterAccountUsername" => "ipartial"])]
#[ApiFilter(BooleanFilter::class, properties: ["active" => "exact"])]
class TwitterAccountToFollow
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private Uuid $id;

    #[ORM\Column(name: 'twitter_account_name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(iri: "https://schema.org/name")]
    private ?string $twitterAccountName = null;

    #[ORM\Column(name: 'twitter_account_username', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    private ?string $twitterAccountUsername = null;

    #[ORM\Column(name: 'twitter_account_id', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(writable: false, iri: "https://schema.org/identifier")]
    private ?string $twitterAccountId = null;

    #[ORM\Column(name: 'active', type: 'boolean', nullable: false)]
    private bool $active = false;

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTwitterAccountName(): ?string
    {
        return $this->twitterAccountName;
    }

    /**
     * @param string|null $twitterAccountName
     */
    public function setTwitterAccountName(?string $twitterAccountName): void
    {
        $this->twitterAccountName = $twitterAccountName;
    }

    /**
     * @return string|null
     */
    public function getTwitterAccountUsername(): ?string
    {
        return $this->twitterAccountUsername;
    }

    /**
     * @param string|null $twitterAccountUsername
     */
    public function setTwitterAccountUsername(?string $twitterAccountUsername): void
    {
        $twitterAccountUsername = str_replace(' ', '', $twitterAccountUsername);
        if($twitterAccountUsername[0] !== '@') {
            $twitterAccountUsername = '@' . $twitterAccountUsername;
        }

        $this->twitterAccountUsername = $twitterAccountUsername;
    }

    /**
     * @return string|null
     */
    public function getTwitterAccountId(): ?string
    {
        return $this->twitterAccountId;
    }

    /**
     * @param string|null $twitterAccountId
     */
    public function setTwitterAccountId(?string $twitterAccountId): void
    {
        $this->twitterAccountId = $twitterAccountId;
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
