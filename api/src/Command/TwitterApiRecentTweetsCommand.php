<?php

declare(strict_types=1);

namespace App\Command;

use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Reward;
use App\Entity\Tweet;
use App\Entity\TwitterAccountToFollow;
use App\Repository\GameRepository;
use App\Repository\LotRepository;
use App\Repository\PlayerRepository;
use App\Repository\RewardRepository;
use App\Repository\TweetReplyRepository;
use App\Repository\TweetRepository;
use App\Repository\TwitterAccountToFollowRepository;
use App\Repository\TwitterHashtagRepository;
use App\Twitter\TwitterApi;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

#[AsCommand(
    name: 'app:comment:getRecentTweets',
    description: 'Get recent tweets about DB active hashtags to update DB and reply to play a game for people who follow DB active accounts to follow',
)]
class TwitterApiRecentTweetsCommand extends Command
{
    public function __construct(private readonly TwitterApi $twitterApi, private readonly PlayerRepository $playerRepository, private readonly TweetRepository $tweetRepository, private readonly GameRepository $gameRepository, private readonly LotRepository $lotRepository, private readonly RewardRepository $rewardRepository, private readonly TweetReplyRepository $tweetReplyRepository, private readonly TwitterAccountToFollowRepository $twitterAccountToFollowRepository, private readonly TwitterHashtagRepository $twitterHashtagRepository, private readonly string $communicationWebsiteUrl)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('update-db', null, InputOption::VALUE_NONE, 'Update database')
            ->addOption('reply', null, InputOption::VALUE_NONE, 'Reply with game URL');
    }

    private const DEFAULTS_TWEETS_REPLIES = [
        ['id'=>'on_new_game', 'reply'=>'Hey %player_name% (%@userhandle%), merci de participer à notre jeu ! '.PHP_EOL.'Pour avoir plus d\'informations sur le jeu voici notre site web : %website_url%'],
        ['id'=>'game_already_generated_less_than_a_day_ago', 'reply'=>'Merci %player_name% (%@userhandle%) de parler de nous.'.PHP_EOL.'Malheureusement tu as déjà joué il y a moins de 24h, tu pourras rejouer une fois que cela fera plus d\'une journée ! '.PHP_EOL.'Pour plus d\'informations tu peux consulter notre site web : %website_url%'],
        ['id'=>'need_to_follow_us','reply'=>'Merci %player_name% (%@userhandle%) de parler de nous. '.PHP_EOL.'Malheureusement tu n\'es pas encore éligible pour pouvoir participer au jeu. Pour l\'être tu dois suivre au moins un des comptes nécessaires. '.PHP_EOL.'Pour plus d\'informations tu peux consulter notre site web : %website_url%']
    ];

    private function selectDefaultTweetReplieById(string $id): ?array
    {
        foreach(self::DEFAULTS_TWEETS_REPLIES as $row) {
            if($row['id'] === $id) {
                return $row;
            }
        }
        return null;
    }

    /**
     * @throws NonUniqueResultException
     */
    private function getTweetReplyMessage(string $id, string $name, string $userhandle): string
    {
        $message = $this->tweetReplyRepository->findOneByName($id)?->getMessage($name, $userhandle);
        if(null !== $message) {
            return $message;
        }

        $message = $this->selectDefaultTweetReplieById($id);
        if(null === $message) {
            return '';
        }

        return str_replace(array('%player_name%', '%@userhandle%', '%website_url%'), array($name, '@' . $userhandle, $this->communicationWebsiteUrl), $message['reply']);
    }

    /**
     * @throws TwitterOAuthException
     */
    private function getRecentTweets(string $hashtag): ?Object
    {
        $params = [
            'query' => $hashtag,
            'expansions' => 'author_id',
            'tweet.fields' => 'created_at',
        ];

        $tweets = $this->twitterApi->get('tweets/search/recent', $params);

        return $tweets->meta->result_count > 0 ? $tweets : null;
    }

    /**
     * @throws TwitterOAuthException
     */
    private function setUser(Object $tweets, Object $tweet, int $index): ?Object
    {
        $user = null;
        try {
            $user = $tweets->includes->users[$index]->id === $tweet->author_id ? $tweets->includes->users[$index] : throw new Exception();
        } catch (Exception) {
            foreach ($tweets->includes->users as $tweetUser) {
                if ($tweetUser->id === $tweet->author_id) {
                    $user = $tweetUser;
                    break;
                }
            }

            if (null === $user) {
                $user = $this->twitterApi->get('users/'.$tweet->author_id);
                $user = $user->data ?? null;
            }
        }

        return $user;
    }

    /**
     * @throws TwitterOAuthException
     */
    private function following(string $userId): bool
    {
        $twitterAccountsToFollow = $this->twitterAccountToFollowRepository->getAllActive();

        foreach ($twitterAccountsToFollow as $twitterAccountToFollow) {
            $friendships = $this->twitterApi->get('friendships/show', [
                'source_id' => $userId,
                'target_id' => $twitterAccountToFollow->getTwitterAccountId(),
            ], '1.1');

            if ($friendships->relationship->source->following) {
                return true;
            }
        }

        return false;
    }

    /**
     * @throws NonUniqueResultException
     * @throws TwitterOAuthException
     */
    private function notFollowAccounts(Object $user, Object $tweet): void
    {
        $message = $this->getTweetReplyMessage('need_to_follow_us', $user->name, $user->username);
        $this->newReply($tweet->id, $message);
    }

    /**
     * @throws TwitterOAuthException
     */
    private function newReply(string $tweetId, string $message): void
    {
        $params = [
            'text' => $message,
            'reply' => [
                'in_reply_to_tweet_id' => $tweetId,
            ],
        ];

        $this->twitterApi->post('tweets', $params);
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws NonUniqueResultException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $hashtags = $this->twitterHashtagRepository->getAllActive();

        foreach ($hashtags as $hashtag) {
            $tweets = $this->getRecentTweets($hashtag->getHashtag());

            if (!$tweets) {
                $io->success('Aucun tweet trouvé pour : '.$hashtag->getHashtag());
                continue;
            }

            $io->success('Tweets trouvés pour : '.$hashtag->getHashtag());

            if (!$input->getOption('update-db')) {
                continue;
            }

            foreach ($tweets->data as $index => $tweet) {
                $tweetAlreadyExists = $this->tweetRepository->findOneByTweetId($tweet->id);

                if (null !== $tweetAlreadyExists) {
                    continue;
                }

                if (null === ($user = $this->setUser($tweets, $tweet, $index))) {
                    $io->error('User not found for the tweet n°'.$tweet->id);
                    continue;
                }

                $player = $this->playerRepository->findOneByTwitterAccountId($user->id);
                $lastGame = null;

                if (null === $player) {
                    $player = new Player();
                    $player->setName($user->name);
                    $player->setUsername($user->username);
                    $player->setTwitterAccountId($user->id);

                    $this->playerRepository->persistAndFlush($player, true);
                } else {
                    $lastGame = $this->gameRepository->findOneByPlayer($player);
                }

                $recentTweet = new Tweet();
                $recentTweet->setPlayer($player);
                $recentTweet->setTweetId($tweet->id);

                $this->tweetRepository->persistAndFlush($recentTweet, true);

                if (!$this->following($user->id)) {
                    if ($input->getOption('reply')) {
                        $this->notFollowAccounts($user, $tweet);
                    }
                    continue;
                }

                if (null !== $lastGame && null !== $lastGame->getPlayDate() && date_diff($lastGame->getPlayDate(), new \DateTime())->d < 1) {
                    if ($input->getOption('reply')) {
                        $message = $this->getTweetReplyMessage('game_already_generated_less_than_a_day_ago', $player->getName(), $player->getUsername());
                        $this->newReply($tweet->id, $message);
                    }
                    continue;
                }

                $reward = new Reward();
                $reward->setDistributed(false);

                $randomLot = $this->lotRepository->getRandom();

                if (\count($randomLot) > 0) {
                    $reward->setLot($randomLot[0]);
                } else {
                    $io->error('No lot available');
                    $this->tweetRepository->removeAndFlush($recentTweet, true);
                    $this->playerRepository->removeAndFlush($player, true);

                    return Command::FAILURE;
                }

                $game = new Game();
                $game->setTweet($recentTweet);
                $game->setPlayer($player);
                $game->setReward($reward);

                if ($this->gameRepository->persistAndFlush($game, true)) {
                    if ($input->getOption('reply')) {
                        $message = $this->getTweetReplyMessage('on_new_game', $player->getName(), $player->getUsername());
                        $this->newReply($tweet->id, $message);
                    }
                } else {
                    $reward->getLot()->setQuantity($reward->getLot()->getQuantity() + 1);
                    $this->lotRepository->persistAndFlush($reward->getLot(), true);
                    $this->rewardRepository->removeAndFlush($reward, true);
                    $this->tweetRepository->removeAndFlush($recentTweet, true);
                }
            }
        }

        $io->success('Database updated successfully');

        return Command::SUCCESS;
    }
}
