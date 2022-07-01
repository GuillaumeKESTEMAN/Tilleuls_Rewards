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
use phpDocumentor\Reflection\Types\Boolean;
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
    description: 'Add a short description for your command',
)]
class TwitterApiRecentTweetsCommand extends Command
{
    private PlayerRepository $playerRepository;
    private TweetRepository $tweetRepository;
    private GameRepository $gameRepository;

    public function __construct(PlayerRepository $playerRepository, TweetRepository $tweetRepository, GameRepository $gameRepository)
    {
        $this->playerRepository = $playerRepository;
        $this->tweetRepository = $tweetRepository;
        $this->gameRepository = $gameRepository;

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
    private function followMe(array $data): boolean
    {
        $myUrl = 'users/';
        $me = TwitterApiService::makeAnGetTwitterApiRequest($myUrl);
        foreach ($data as $follow) {
            if($follow->id === $me->id) {
                return true;
            }
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

                        $userFollowingUrl = 'users/' . $user->id . '/following';
                        $userFollowing = TwitterApiService::makeAnGetTwitterApiRequest($userFollowingUrl);
                        if ($userFollowing->meta->result_count > 0 && $this->followMe((array)$userFollowing->data)) {
                            if (null === $player) {
                                $player = new Player();
                                $player->setName($user->name);
                                $player->setTwitterAccountId($user->id);

                                $this->playerRepository->add($player, true);

                                $io->info($user->username . ' added !');
                            }

                            $recentTweet = new Tweet();
                            $recentTweet->setPlayer($player);
                            $recentTweet->setTweetId($tweet->id);
                            $recentTweet->setCreationDate(new DateTime($tweet->created_at));

                            $this->tweetRepository->add($recentTweet, true);

                            $io->info('tweet n°' . $tweet->id . ' added !');

                            $game = new Game();
                            $game->setTweet($recentTweet);
                            $game->setCreationDate(new \DateTime);
                            $game->setPlayer($player);

                            if ($this->gameRepository->add($game, true)) {
                                $io->info('Game created successfully !');

                                if ($input->getOption('reply-game-url')) {
                                    $postTweetUrl = 'tweets';
                                    $params = [
                                        'text' => 'Thanks ' . $player->getName() . ' to talk about us.\n We want to give you a little gift but to get it you must play a little game 😁\n' . $game->getUrl(),
                                        'reply' => $tweet->id
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
