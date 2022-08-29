<?php

declare(strict_types=1);

namespace App\Command;

use Abraham\TwitterOAuth\TwitterOAuthException;
use ApiPlatform\Symfony\Validator\Exception\ValidationException;
use ApiPlatform\Validator\ValidatorInterface;
use App\Entity\Game;
use App\Entity\Player;
use App\Entity\Reward;
use App\Entity\Tweet;
use App\Exception\NoActiveHashtagException;
use App\Exception\NoActiveTwitterAccountToFollowException;
use App\Exception\NoLotAvailableException;
use App\Exception\TweetReplyNotFoundException;
use App\Repository\GameRepository;
use App\Repository\LotRepository;
use App\Repository\PlayerRepository;
use App\Repository\TweetReplyRepository;
use App\Repository\TweetRepository;
use App\Repository\TwitterAccountToFollowRepository;
use App\Repository\TwitterHashtagRepository;
use App\Twitter\TwitterApi;
use App\Visitor\MessageNormalizer\MessageNormalizer;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

#[AsCommand(
    name: 'app:get-recent-tweets',
    description: 'Get recent tweets about DB active hashtags to update DB and reply to play a game for people who follow DB active accounts to follow',
)]
final class TwitterApiRecentTweetsCommand extends Command
{
    private const DEFAULTS_TWEETS_REPLIES = [
        ['id' => 'on_new_game', 'reply' => 'Hey %nom% (%@username%), merci de participer à notre jeu ! '.\PHP_EOL.'Pour avoir plus d\'informations sur le jeu voici notre site web : %site_web%'],
        ['id' => 'game_already_generated_less_than_a_day_ago', 'reply' => 'Merci %nom% (%@username%) de parler de nous.'.\PHP_EOL.'Malheureusement tu as déjà joué il y a moins de 24h, tu pourras rejouer une fois que cela fera plus d\'une journée ! '.\PHP_EOL.'Pour plus d\'informations tu peux consulter notre site web : %site_web%'],
        ['id' => 'need_to_follow_us', 'reply' => 'Merci %nom% (%@username%) de parler de nous. '.\PHP_EOL.'Malheureusement tu n\'es pas encore éligible pour pouvoir participer au jeu. Pour l\'être tu dois suivre les comptes nécessaires. '.\PHP_EOL.'Pour plus d\'informations tu peux consulter notre site web : %site_web%'],
        ['id' => 'no_more_available_lots', 'reply' => 'Nous n\'avons malheureusement plus aucun lot de disponible... '.\PHP_EOL.'Retente ta chance un autre jour !'],
    ];

    public function __construct(
        private readonly TwitterApi $twitterApi,
        private readonly PlayerRepository $playerRepository,
        private readonly TweetRepository $tweetRepository,
        private readonly GameRepository $gameRepository,
        private readonly LotRepository $lotRepository,
        private readonly TweetReplyRepository $tweetReplyRepository,
        private readonly TwitterAccountToFollowRepository $twitterAccountToFollowRepository,
        private readonly TwitterHashtagRepository $twitterHashtagRepository,
        private readonly string $communicationWebsiteUrl,
        private readonly LoggerInterface $logger,
        private readonly ValidatorInterface $validator,
        private readonly MessageNormalizer $messageNormalizer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('update-db', null, InputOption::VALUE_NONE, 'Update database')
            ->addOption('reply', null, InputOption::VALUE_NONE, 'Reply with game URL');
    }

    private function selectDefaultTweetReplyById(string $id): ?array
    {
        foreach (self::DEFAULTS_TWEETS_REPLIES as $row) {
            if ($row['id'] === $id) {
                return $row;
            }
        }
        $this->logger->error("'$id' was not found in DEFAULTS_TWEETS_REPLIES");

        return null;
    }

    /**
     * @throws NonUniqueResultException
     */
    private function getTweetReplyMessage(string $id, string $name, string $userhandle): string
    {
        $message = $this->tweetReplyRepository->findOneByName($id)?->message;

        if (null !== $message) {
            $params = [
                'nom' => $name,
                'username' => $userhandle,
                'site_web' => $this->communicationWebsiteUrl,
            ];

            return $this->messageNormalizer->normalize($message, $params);
        }

        $message = $this->selectDefaultTweetReplyById($id);
        if (null === $message) {
            throw new TweetReplyNotFoundException();
        }

        return str_replace(['%nom%', '%@username%', '%site_web%'], [$name, '@'.$userhandle, $this->communicationWebsiteUrl], $message['reply']);
    }

    /**
     * @throws TwitterOAuthException
     * @throws NonUniqueResultException
     */
    private function getRecentTweets(string $hashtags): ?object
    {
        $tweets = $this->twitterApi->getRecentTweets($hashtags);

        return $tweets->meta->result_count > 0 ? $tweets : null;
    }

    /**
     * @throws TwitterOAuthException
     */
    private function setUser(object $tweets, object $tweet, int $index): ?object
    {
        $user = \array_key_exists($index, $tweets->includes->users) && $tweets->includes->users[$index]->id === $tweet->author_id ? $tweets->includes->users[$index] : null;

        if (null !== $user) {
            return $user;
        }

        foreach ($tweets->includes->users as $tweetUser) {
            if ($tweetUser->id === $tweet->author_id) {
                return $tweetUser;
            }
        }

        try {
            $user = $this->twitterApi->getUser($tweet->author_id);
            $user = $user->data ?? null;

            if (null === $user) {
                $this->logger->warning(
                    "Twitter user n°$tweet->author_id not found for the tweet n°$tweet->id",
                    [
                        'tweet' => $tweet,
                    ]
                );
            }
        } catch (BadRequestHttpException $e) {
            $this->logger->critical(
                'Twitter API get request (users/) error : '.$e->getMessage(),
                [
                    'tweet' => $tweet,
                    'error' => $e,
                ]
            );
        }

        return $user;
    }

    /**
     * @throws TwitterOAuthException
     */
    private function following(string $userId, array $accountsToFollow): bool
    {
        foreach ($accountsToFollow as $accountToFollow) {
            try {
                $friendships = $this->twitterApi->isFollowing($userId, $accountToFollow->twitterAccountId);

                if (!$friendships) {
                    return false;
                }
            } catch (BadRequestHttpException $e) {
                $this->logger->critical(
                    'Twitter API get request (friendships/show) error : '.$e->getMessage(),
                    [
                        'error' => $e,
                    ]
                );
            }
        }

        return true;
    }

    /**
     * @throws NonUniqueResultException
     * @throws TwitterOAuthException
     */
    private function sendMissingAccountToFollowReply(object $user, object $tweet): void
    {
        $this->newReply($this->getTweetReplyMessage('need_to_follow_us', $user->name, $user->username), $tweet->id);
    }

    /**
     * @throws TwitterOAuthException
     */
    private function newReply(string $message, string $tweetId): void
    {
        try {
            $this->twitterApi->reply($message, $tweetId);
        } catch (BadRequestHttpException $e) {
            $this->logger->critical(
                'Twitter API post request (tweets) error : '.$e->getMessage(),
                [
                    'error' => $e,
                ]
            );
        }
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
        $accountsToFollow = $this->twitterAccountToFollowRepository->getAllActive();
        $databaseUpdated = false;

        $this->logger->notice(
            'Command state: update-db: '.$input->getOption('update-db').', reply: '.$input->getOption('reply'),
            [
                'Active hashtags for command' => $hashtags,
                'Active Twitter accounts to follow for command' => $accountsToFollow,
            ]
        );

        if (\count($hashtags) <= 0) {
            $io->error('No active hashtag for the TwitterApiRecentTweetsCommand');
            throw new NoActiveHashtagException();
        }

        if (\count($accountsToFollow) <= 0) {
            $io->error('No active twitter account to follow for the TwitterApiRecentTweetsCommand');
            throw new NoActiveTwitterAccountToFollowException();
        }

        $stringHashtags = array_map(static function ($hashtag) {
            return $hashtag->getHashtag();
        }, $hashtags);
        $stringHashtags = implode(' ', $stringHashtags);

        try {
            $tweets = $this->getRecentTweets($stringHashtags);
        } catch (BadRequestHttpException $e) {
            $this->logger->critical(
                'Twitter API get request (tweets/search/recent) error : '.$e->getMessage(),
                [
                    'error' => $e,
                ]
            );

            $io->error('Twitter API get request (tweets/search/recent) error : '.$e->getMessage());

            return Command::FAILURE;
        }

        if (null === $tweets) {
            $io->success('Aucun tweet trouvé pour : '.$stringHashtags);
            $this->logger->notice('Aucun tweet trouvé pour : '.$stringHashtags);

            return Command::SUCCESS;
        }

        $io->success('Tweets trouvés pour : '.$stringHashtags);
        $this->logger->notice('Tweets trouvés pour : '.$stringHashtags);

        if (!$input->getOption('update-db')) {
            return Command::SUCCESS;
        }

        foreach ($tweets->data as $index => $tweet) {
            $tweetAlreadyExists = $this->tweetRepository->findOneByTweetId($tweet->id);

            if (null !== $tweetAlreadyExists) {
                continue;
            }

            if (null === ($user = $this->setUser($tweets, $tweet, $index))) {
                $io->error("Twitter user n°$tweet->author_id not found for the tweet n°$tweet->id");
                continue;
            }

            $player = $this->playerRepository->findOneByTwitterAccountId($user->id);

            if (null === $player || ($player->getUsername() !== '@'.$user->username || $player->name !== $user->name)) {
                if (null === $player) {
                    $player = new Player();
                    $player->twitterAccountId = $user->id;
                }
                $player->name = $user->name;
                $player->setUsername($user->username);

                $this->playerRepository->persistAndFlush($player, true);
            }

            $lastGameDate = $player->lastPlayDate;

            $recentTweet = new Tweet();
            $recentTweet->player = $player;
            $recentTweet->tweetId = $tweet->id;
            $recentTweet->creationDate = new DateTime($tweet->created_at);

            $this->tweetRepository->persistAndFlush($recentTweet, true);

            $databaseUpdated = true;

            if (!$this->following($user->id, $accountsToFollow)) {
                if ($input->getOption('reply')) {
                    try {
                        $this->sendMissingAccountToFollowReply($user, $tweet);
                    } catch (TweetReplyNotFoundException $e) {
                        $this->logger->error(
                            "No tweet reply message found for 'need_to_follow_us' in TwitterApiRecentTweetsCommand",
                            [
                                'tweet_reply_id' => 'need_to_follow_us',
                                'error' => $e,
                            ]
                        );

                        return Command::FAILURE;
                    }
                }
                continue;
            }

            if (null !== $lastGameDate && date_diff($lastGameDate, new DateTime())->d < 1) {
                if ($input->getOption('reply')) {
                    try {
                        $message = $this->getTweetReplyMessage('game_already_generated_less_than_a_day_ago', $player->name, $player->getUsername());
                        $this->newReply($message, $tweet->id);
                    } catch (TweetReplyNotFoundException $e) {
                        $this->logger->error(
                            "No tweet reply message found for 'game_already_generated_less_than_a_day_ago' in TwitterApiRecentTweetsCommand",
                            [
                                'tweet_reply_id' => 'game_already_generated_less_than_a_day_ago',
                                'error' => $e,
                            ]
                        );

                        return Command::FAILURE;
                    }
                }
                continue;
            }

            $reward = new Reward();
            $reward->distributed = false;

            $randomLot = $this->lotRepository->getRandom();

            if (\count($randomLot) > 0) {
                $reward->lot = $randomLot[0];
            } else {
                $io->error('No lot available');
                if ($input->getOption('reply')) {
                    try {
                        $message = $this->getTweetReplyMessage('no_more_available_lots', $player->name, $player->getUsername());
                        $this->newReply($message, $tweet->id);
                    } catch (TweetReplyNotFoundException $e) {
                        $this->logger->error(
                            "No tweet reply message found for 'no_more_available_lots' in TwitterApiRecentTweetsCommand",
                            [
                                'tweet_reply_id' => 'no_more_available_lots',
                                'error' => $e,
                            ]
                        );
                    }
                }

                throw new NoLotAvailableException();
            }

            $game = new Game();
            $game->tweet = $recentTweet;
            $game->player = $player;
            $game->reward = $reward;

            try {
                $this->validator->validate($game);
                $this->gameRepository->persistAndFlush($game, true);
            } catch (ValidationException $e) {
                $io->error($e->getMessage());
                $this->logger->error($e->getMessage(), (array) $e);
                continue;
            }

            $player->lastPlayDate = new DateTime();
            $this->playerRepository->persistAndFlush($player, true);

            if ($input->getOption('reply')) {
                try {
                    $message = $this->getTweetReplyMessage('on_new_game', $player->name, $player->getUsername());
                    $this->newReply($message, $tweet->id);
                } catch (TweetReplyNotFoundException $e) {
                    $this->logger->error(
                        "No tweet reply message found for 'on_new_game' in TwitterApiRecentTweetsCommand",
                        [
                            'tweet_reply_id' => 'on_new_game',
                            'error' => $e,
                        ]
                    );

                    return Command::FAILURE;
                }
            }
        }

        if ($databaseUpdated) {
            $io->success('Database updated successfully');
        }

        return Command::SUCCESS;
    }
}
