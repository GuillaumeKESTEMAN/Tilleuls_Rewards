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
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Repository\TweetReplyRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TweetReplyRepository::class)]
#[UniqueEntity('name')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Put(),
        new Delete()
    ],
    order: ["name" => "ASC"]
)]
#[ApiFilter(SearchFilter::class, properties: ["name" => "partial"])]
class TweetReply
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    #[ApiProperty(types: ["https://schema.org/identifier"])]
    private Uuid $id;

    #[ORM\Column(name: 'name', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(types: ["https://schema.org/name"])]
    private ?string $name = null;

    #[ORM\Column(name: 'message', type: 'string', nullable: false)]
    #[Assert\NotBlank]
    #[ApiProperty(description: 'Message that will be sent to players. To write the player name in the message, write : %player_name%, same for the userhandle mention (%@userhandle%) and same for communication website link (%website_url%)', types: ["https://schema.org/Message"])]
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
     * @param string|null $name
     * @param string|null $userhandle
     * @param string|null $gameLink
     * @return string|null
     */
    public function getMessage(?string $name = null, ?string $userhandle = null, ?string $gameLink = null): ?string
    {
        if (null !== $name) {
            return str_replace("%player_name%", $name, $this->message);
        }

        if (null !== $userhandle) {
            return str_replace("%@userhandle%", "@".$userhandle, $this->message);
        }

        if (null !== $gameLink) {
            return str_replace("%website_url%", $gameLink, $this->message);
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
