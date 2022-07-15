<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TweetReplyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TweetReplyRepository::class)]
#[ORM\Table(name: '`tweet_reply`')]
#[UniqueEntity('name')]
#[ApiResource(
    collectionOperations: [
        "get",
        "post"
    ],
    itemOperations: [
        "get",
        "put",
        "delete"
    ],
    order: ["name" => "ASC"],
    security: "is_granted('ROLE_ADMIN')"
)]
#[ApiFilter(SearchFilter::class, properties: ["name" => "partial"])]
class TweetReply
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(iri: "https://schema.org/identifier")]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(iri: "https://schema.org/name")]
    private ?string $name = null;

    #[ORM\Column(name: 'message', type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(description: 'Message that will be sent to players. To write the username in the message, write : %username%, same for the userhandle (%@userhandle%) and same for game link (%game%)', iri: "https://schema.org/Message")]
    private ?string $message = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string|null $username
     * @param string|null $userhandle
     * @param string|null $gameLink
     * @return string|null
     */
    public function getMessage(?string $username = null, ?string $userhandle = null, ?string $gameLink = null): ?string
    {
        if (null !== $username) {
            return str_replace("%username%", $username, $this->message);
        }

        if (null !== $userhandle) {
            return str_replace("%@userhandle%", "@".$userhandle, $this->message);
        }

        if (null !== $gameLink) {
            return str_replace("%game%", $gameLink, $this->message);
        }

        return $this->message;
    }

    /**
     * @param string|null $message
     */
    public function setMessage(?string $message): void
    {
        $this->message = $message;
    }
}
