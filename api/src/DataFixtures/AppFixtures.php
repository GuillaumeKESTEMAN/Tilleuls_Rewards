<?php

namespace App\DataFixtures;

use App\Factory\GameFactory;
use App\Factory\LotFactory;
use App\Factory\PlayerFactory;
use App\Factory\RewardFactory;
use App\Factory\Test\GameTestFactory;
use App\Factory\Test\LotTestFactory;
use App\Factory\Test\MediaObjectTestFactory;
use App\Factory\Test\PlayerTestFactory;
use App\Factory\Test\RewardTestFactory;
use App\Factory\Test\TweetReplyTestFactory;
use App\Factory\Test\TweetTestFactory;
use App\Factory\Test\TwitterAccountToFollowTestFactory;
use App\Factory\Test\TwitterHashtagTestFactory;
use App\Factory\TwitterHashtagFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct()
    {
    }

    public function load(ObjectManager $manager): void
    {
        if($_ENV['APP_ENV'] === 'dev') {
            LotFactory::new()->createMany(20);
            PlayerFactory::new()->createMany(20);
            GameFactory::new()->createMany(20);
            RewardFactory::new()->createMany(20);
            TwitterHashtagFactory::new()->createMany(20);
        }

        if($_ENV['APP_ENV'] === 'test') {
            LotFactory::new()->createMany(59);
            PlayerFactory::new()->createMany(59);
            PlayerTestFactory::new()->create();
            TweetTestFactory::new()->create();
            GameFactory::new()->createMany(59);
            GameTestFactory::new()->create();
            RewardFactory::new()->createMany(59);
            RewardTestFactory::new()->create();
            LotTestFactory::new()->create();
            TwitterHashtagFactory::new()->createMany(59);
            TwitterHashtagTestFactory::new()->create();
            MediaObjectTestFactory::new()->create();
            TwitterAccountToFollowTestFactory::new()->create();
            TweetReplyTestFactory::new()->create();
        }

        $manager->flush();
    }
}
