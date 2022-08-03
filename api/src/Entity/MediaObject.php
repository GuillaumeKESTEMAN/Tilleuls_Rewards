<?php
// api/src/Entity/MediaObject.php
namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\CreateMediaObjectActionController;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity]
#[ORM\Table(name: '`media_object`')]
#[UniqueEntity('filePath')]
#[UniqueEntity('name')]
#[ApiResource(
    types: ['https://schema.org/MediaObject'],
    operations: [
        new GetCollection(),
        new Post(
            controller: CreateMediaObjectActionController::class,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                    ],
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            validationContext: ['groups' => ['Default', 'media_object_create']],
            deserialize: false,
        ),
        new Get(),
        new Delete()
    ],
    normalizationContext: ['groups' => ['media_object:read']],
    mercure: ["private" => true],
    order: ["id" => "DESC"]
)]
#[ApiFilter(SearchFilter::class, properties: ["filePath" => "partial", "name" => "partial"])]
class MediaObject
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[ORM\CustomIdGenerator(class: "doctrine.uuid_generator")]
    private ?Uuid $id = null;

    #[ORM\Column(name: 'name', type: 'string', length: 255, unique: true, nullable: false)]
    #[Assert\NotBlank(groups: ['media_object_create'])]
    #[ApiProperty(types: ['https://schema.org/name'])]
    #[Groups(['media_object:read'])]
    public ?string $name = null;

    #[ApiProperty(writable: false, types: ['https://schema.org/contentUrl'])]
    #[Groups(['media_object:read'])]
    public ?string $contentUrl = null;

    /**
     * @Vich\UploadableField(mapping="media_object", fileNameProperty="filePath")
     */
    #[Assert\NotNull(groups: ['media_object_create'])]
    #[Assert\NotBlank(groups: ['media_object_create'])]
    #[Assert\Image([
        'maxSize' => "5M",
        'minWidth' => 100,
        'maxWidth' => 2000,
        'minHeight' => 100,
        'maxHeight' => 2000,
        'mimeTypes' => [
            "image/jpeg",
            "image/jpg",
            "image/png",
        ],
    ], groups: ['media_object_create'])]
    private ?File $file = null;

    #[ORM\Column(name: 'file_path', unique: true, nullable: true)]
    private ?string $filePath = null;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     */
    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     */
    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

}
