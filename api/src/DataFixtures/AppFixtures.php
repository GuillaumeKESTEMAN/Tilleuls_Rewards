<?php

namespace App\DataFixtures;

use App\Factory\GameFactory;
use App\Factory\LotFactory;
use App\Factory\PlayerFactory;
use App\Factory\RewardFactory;
use App\Factory\TweetFactory;
use App\Factory\TwitterHashtagFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        LotFactory::new()->createMany(10);
        PlayerFactory::new()->createMany(10);
        GameFactory::new()->createMany(10);
        RewardFactory::new()->createMany(10);
        TwitterHashtagFactory::new()->createMany(10);

        $manager->flush();
    }
}
