<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Repository\GameRepository;
use App\State\GamePutProcessor;
use App\Validator\HasNotPlayedForADay;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[UniqueEntity('reward')]
#[UniqueEntity('tweet')]
#[
    ApiResource(
        types: ['https://schema.org/VideoGame'],
        operations: [
            new GetCollection(security: 'is_granted("ROLE_ADMIN") || is_granted("ROLE_GAME")'),
            new Get(),
            new Put(
                denormalizationContext: ['groups' => ['put']],
                security: '(is_granted("ROLE_ADMIN") || is_granted("ROLE_GAME")) && object.score === null',
                validationContext: ['groups' => ['putValidation']],
                processor: GamePutProcessor::class
            ),
        ],
        mercure: ['private' => true],
        order: ['playDate' => 'DESC'],
        paginationClientItemsPerPage: true
    )
]
#[ApiFilter(SearchFilter::class, properties: ['player.username' => 'partial'])]
#[ApiFilter(DateFilter::class, properties: ['playDate'])]
#[ApiFilter(OrderFilter::class, properties: ['tweet', 'player.username', 'score', 'playDate'])]
class Game
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ApiProperty(types: ['https://schema.org/identifier'])]
    private Uuid $id;

    #[ORM\OneToOne(targetEntity: Tweet::class, cascade: ['persist'])]
    #[ORM\JoinColumn(unique: true)]
    #[ApiProperty(types: ['https://schema.org/SocialMediaPosting'])]
    public ?Tweet $tweet = null;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[HasNotPlayedForADay]
    public ?Player $player = null;

    #[ORM\Column(name: 'score', type: 'integer', nullable: true)]
    #[ApiProperty(types: ['https://schema.org/Rating'])]
    #[Groups('put')]
    #[Assert\NotBlank(groups: ['putValidation'])]
    #[Assert\PositiveOrZero(groups: ['putValidation'])]
    public ?int $score = null;

    #[ORM\Column(name: 'play_date', type: 'datetime')]
    #[ApiProperty(types: ['https://schema.org/DateTime'])]
    #[Assert\NotBlank]
    #[Assert\Type(\DateTimeInterface::class)]
    public ?DateTime $playDate = null;

    #[ORM\OneToOne(inversedBy: 'game', targetEntity: Reward::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(unique: true)]
    public ?Reward $reward = null;

    public function __construct()
    {
        $this->playDate = new DateTime();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
