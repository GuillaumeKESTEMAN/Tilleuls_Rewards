<?php

namespace App\Factory\Test;

use App\Entity\MediaObject;
use Symfony\Component\Filesystem\Filesystem;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<MediaObject>
 *
 * @method static MediaObject|Proxy createOne(array $attributes = [])
 * @method static MediaObject[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static MediaObject|Proxy find(object|array|mixed $criteria)
 * @method static MediaObject|Proxy findOrCreate(array $attributes)
 * @method static MediaObject|Proxy first(string $sortedField = 'id')
 * @method static MediaObject|Proxy last(string $sortedField = 'id')
 * @method static MediaObject|Proxy random(array $attributes = [])
 * @method static MediaObject|Proxy randomOrCreate(array $attributes = [])
 * @method static MediaObject[]|Proxy[] all()
 * @method static MediaObject[]|Proxy[] findBy(array $attributes)
 * @method static MediaObject[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static MediaObject[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method MediaObject|Proxy create(array|callable $attributes = [])
 */
final class MediaObjectTestFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        $fs = new Filesystem();

        $root = explode("/",__DIR__);
        $root = array_slice($root, 0, -3);
        $root = implode("/", $root);

        $originPath = $root .'/fixtures/files/image.jpg';
        $targetPath = $root .'/fixtures/files/test_image.jpg';
        $fs->copy($originPath, $targetPath, true);

        $fs->touch($root . '/fixtures/files/invalid_file.txt');
        $fs->appendToFile($root . '/fixtures/files/invalid_file.txt', 'My invalid file !!!');

        return [
            'name' => self::faker()->text(),
            'filePath' => 'image.jpg',
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(MediaObject $mediaObject): void {})
        ;
    }

    protected static function getClass(): string
    {
        return MediaObject::class;
    }
}
