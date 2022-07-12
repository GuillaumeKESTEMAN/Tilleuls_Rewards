<?php

namespace App\DataFixtures;

use App\Factory\GameFactory;
use App\Factory\LotFactory;
use App\Factory\PlayerFactory;
use App\Factory\RewardFactory;
use App\Factory\TweetFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        LotFactory::new()->createMany(10);
        PlayerFactory::new()->createMany(10);
        TweetFactory::new()->createMany(15);
        GameFactory::new()->createMany(10);
        RewardFactory::new()->createMany(10);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
