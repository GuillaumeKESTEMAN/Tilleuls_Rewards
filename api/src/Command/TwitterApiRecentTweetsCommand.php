<?php

namespace App\Command;

use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Tweet;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\TweetRepository;
use App\Twitter\TwitterApi;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

#[AsCommand(
    name: 'app:comment:getRecentTweets',
    description: 'Get recent tweets with a query to update DB and reply to play a game',
)]
class TwitterApiRecentTweetsCommand extends Command
{
    public function __construct(private TwitterApi $twitterApi, private PlayerRepository $playerRepository, private TweetRepository $tweetRepository, private GameRepository $gameRepository)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('query', InputArgument::REQUIRED, 'Argument to the query parameter')
            ->addOption('need-follow', null, InputOption::VALUE_OPTIONAL, 'The user must follow this twitter account to be registered', $_ENV["TWITTER_ACCOUNT_TO_FOLLOW"])
            ->addOption('update-db', null, InputOption::VALUE_NONE, 'Update database')
            ->addOption('reply-game-url', null, InputOption::VALUE_NONE, 'Reply with game URL');
    }

    /**
     * @throws TwitterOAuthException
     */
    private function followingMe(string $userId, string $targetId): bool
    {
        $myUrl = 'friendships/show';
        $params = [
            'source_id' => $userId,
            'target_id' => $targetId
        ];
        $friendships = $this->twitterApi->makeAnGetTwitterApiRequest($myUrl, $params, '1.1');

        return $friendships->relationship->source->following;
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
        $query = $input->getArgument('query');

        $recentTweetsUrl = 'tweets/search/recent';
        $params = [
            'query' => $query,
            'expansions' => 'author_id',
            'tweet.fields' => 'created_at'
        ];

        $tweets = $this->twitterApi->makeAnGetTwitterApiRequest($recentTweetsUrl, $params);
        $tweetsData = $tweets->meta->result_count > 0 ? $tweets->data : null;

        if (!$tweetsData) {
            $io->success('Aucun tweet trouvé');
            return Command::SUCCESS;
        }

        $io->success('Tweets trouvés');

        if (!$input->getOption('update-db')) {
            return Command::SUCCESS;
        }

        foreach ($tweetsData as $index => $tweet) {
            $tweetAlreadyExists = $this->tweetRepository->findOneByTweetId($tweet->id);

            if (null !== $tweetAlreadyExists) {
                continue;
            }

            $user = null;

            try {
                $user = $tweets->includes->users[$index]->id === $tweet->author_id ? $tweets->includes->users[$index] : throw new Exception;
            } catch (Exception) {
                foreach ($tweets->includes->users as $tweetUser) {
                    if ($tweetUser->id === $tweet->author_id) {
                        $user = $tweetUser;
                        break;
                    }
                }

                if (null === $user) {
                    $user = $this->twitterApi->makeAnGetTwitterApiRequest('users/' . $tweet->author_id, []);
                    $user = $user->data;
                }
            }

            if (null === $user) {
                $io->error('User not found for the tweet n°' . $tweet->id);
                continue;
            }

            if (!$this->followingMe($user->id, $input->getOption('need-follow'))) {
                // TODO add tweet back to encourage following the need-follow account
                continue;
            }

            $player = $this->playerRepository->findOneByTwitterAccountId($user->id);
            $lastGame = null;

            if (null === $player) {
                $player = new Player();
                $player->setUsername($user->username);
                $player->setTwitterAccountId($user->id);

                $this->playerRepository->add($player, true);
            } else {
                $lastGame = $this->gameRepository->findOneByPlayer($player);
            }

            if (null === $lastGame || null === $lastGame->getPlayDate() || date_diff($lastGame->getPlayDate(), new \DateTime)->d >= 1) {
                $tweetCreationDate = new DateTime($tweet->created_at, new DateTimeZone('UTC'));
                $tweetCreationDate->setTimezone(new DateTimeZone('Europe/Paris'));

                $recentTweet = new Tweet();
                $recentTweet->setPlayer($player);
                $recentTweet->setTweetId($tweet->id);
                $recentTweet->setCreationDate($tweetCreationDate);

                $this->tweetRepository->add($recentTweet, true);

                $game = new Game();
                $game->setTweet($recentTweet);
                $game->setPlayer($player);

                if ($this->gameRepository->add($game, true)) {
                    if ($input->getOption('reply-game-url')) {
                        $postTweetUrl = 'tweets';
                        // TODO make this configurable :D

                        $params = [
                            'text' => 'Thanks ' . $player->getUsername() . ' to talk about us.' . PHP_EOL . 'We want to give you a little gift but to get it you must play a little game 😁' . PHP_EOL . $game->getUrl(),
                            'reply' => [
                                'in_reply_to_tweet_id' => $tweet->id
                            ]
                        ];
                        $this->twitterApi->makeAnPostTwitterApiRequest($postTweetUrl, $params);
                    }
                }
            } else {
                if ($input->getOption('reply-game-url')) {
                    $postTweetUrl = 'tweets';
                    $params = [
                        'text' => 'Thanks ' . $player->getUsername() . ' to talk about us.' . PHP_EOL . 'But you already got a game link less than a day ago ! (talk again about us tomorrow to get a new game url)' . PHP_EOL . 'This is your previous game link : ' . $lastGame->getUrl(),
                        'reply' => [
                            'in_reply_to_tweet_id' => $tweet->id
                        ]
                    ];
                    $this->twitterApi->makeAnPostTwitterApiRequest($postTweetUrl, $params);
                }
            }
        }

        $io->success('Database updated successfully');

        return Command::SUCCESS;
    }
}
