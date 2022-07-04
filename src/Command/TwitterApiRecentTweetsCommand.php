<?php

namespace App\Command;

use Abraham\TwitterOAuth\TwitterOAuthException;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Tweet;
use App\Repository\GameRepository;
use App\Repository\PlayerRepository;
use App\Repository\TweetRepository;
use App\TwitterApiService;
use DateTime;
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
    private PlayerRepository $playerRepository;
    private TweetRepository $tweetRepository;
    private GameRepository $gameRepository;

    private ?string $myId;

    /**
     * @throws TwitterOAuthException
     */
    public function __construct(PlayerRepository $playerRepository, TweetRepository $tweetRepository, GameRepository $gameRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->tweetRepository = $tweetRepository;
        $this->gameRepository = $gameRepository;

        $myUrl = 'account/verify_credentials';
        $me = TwitterApiService::makeAnGetTwitterApiRequest($myUrl, [], '1.1');
        $this->myId = $me->id ?: null;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('query', InputArgument::OPTIONAL, 'Argument to the query parameter')
            ->addOption('update-db', null, InputOption::VALUE_NONE, 'Update database')
            ->addOption('reply-game-url', null, InputOption::VALUE_NONE, 'Reply with game URL');
    }

    /**
     * @throws TwitterOAuthException
     */
    private function followingMe(string $userId): bool
    {
        if ($this->myId !== null) {
            $myUrl = 'friendships/show';
            $params = [
                'source_id' => $userId,
                'target_id' => $this->myId
            ];
            $friendships = TwitterApiService::makeAnGetTwitterApiRequest($myUrl, $params, '1.1');

            return $friendships->relationship->source->following;
        }
        return false;
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

        if (!$query) {
            $io->error("An argument is required : 'symfony console app:comment:getRecentTweets something'");
            return Command::INVALID;
        }

        $recentTweetsUrl = 'tweets/search/recent';
        $params = [
            'query' => $query,
            'expansions' => 'author_id',
            'tweet.fields' => 'created_at'
        ];

        $tweets = TwitterApiService::makeAnGetTwitterApiRequest($recentTweetsUrl, $params);
        $tweetsData = $tweets->meta->result_count > 0 ? $tweets->data : null;

        if ($tweetsData) {
            $io->success('Tweets trouvés');

            if ($input->getOption('update-db')) {
                foreach ($tweetsData as $index => $tweet) {
                    $recentTweet = $this->tweetRepository->findOneByTweetId($tweet->id);

                    if (null === $recentTweet) {
                        $user = $tweets->includes->users[$index];
                        $player = $this->playerRepository->findOneByTwitterAccountId($user->id);

                        if ($this->followingMe($user->id)) {
                            if (null === $player) {
                                $player = new Player();
                                $player->setName($user->name);
                                $player->setTwitterAccountId($user->id);

                                $this->playerRepository->add($player, true);
                            }

                            $recentTweet = new Tweet();
                            $recentTweet->setPlayer($player);
                            $recentTweet->setTweetId($tweet->id);
                            $recentTweet->setCreationDate(new DateTime($tweet->created_at));

                            $this->tweetRepository->add($recentTweet, true);

                            $game = new Game();
                            $game->setTweet($recentTweet);
                            $game->setCreationDate(new DateTime);
                            $game->setPlayer($player);

                            if ($this->gameRepository->add($game, true)) {
                                if ($input->getOption('reply-game-url')) {
                                    $postTweetUrl = 'tweets';
                                    $params = [
                                        'text' => 'Thanks ' . $player->getName() . ' to talk about us.' . PHP_EOL . ' We want to give you a little gift but to get it you must play a little game 😁' . PHP_EOL . $game->getUrl(),
                                        'reply' => [
                                            'in_reply_to_tweet_id' => $tweet->id
                                        ]
                                    ];
                                    $tweets = TwitterApiService::makeAnPostTwitterApiRequest($postTweetUrl, $params);
                                }
                            }
                        }
                    }
                }
                $io->success('Database updated successfully');
            }
        } else {
            $io->success('Aucun tweet trouvé');
        }

        return Command::SUCCESS;
    }
}
