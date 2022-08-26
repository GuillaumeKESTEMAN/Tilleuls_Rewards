<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826101630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE tweet_reply (id UUID NOT NULL, name VARCHAR(255) NOT NULL, message VARCHAR(270) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_61631baf5e237e06 ON tweet_reply (name)');
        $this->addSql('COMMENT ON COLUMN tweet_reply.id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE twitter_account_to_follow (id UUID NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, twitter_account_id VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_99f3e05f85e0677 ON twitter_account_to_follow (username)');
        $this->addSql('CREATE UNIQUE INDEX uniq_99f3e05322e56fb ON twitter_account_to_follow (twitter_account_id)');
        $this->addSql('COMMENT ON COLUMN twitter_account_to_follow.id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE twitter_hashtag (id UUID NOT NULL, hashtag VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_96c4def35ab52a61 ON twitter_hashtag (hashtag)');
        $this->addSql('COMMENT ON COLUMN twitter_hashtag.id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE tweet (id UUID NOT NULL, player_id UUID DEFAULT NULL, tweet_id VARCHAR(255) NOT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_3d660a3b1041e39b ON tweet (tweet_id)');
        $this->addSql('CREATE INDEX idx_3d660a3b99e6f5df ON tweet (player_id)');
        $this->addSql('COMMENT ON COLUMN tweet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tweet.player_id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE game (id UUID NOT NULL, tweet_id UUID DEFAULT NULL, player_id UUID DEFAULT NULL, reward_id UUID DEFAULT NULL, score INT DEFAULT NULL, play_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_232b318c1041e39b ON game (tweet_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_232b318ce466aca1 ON game (reward_id)');
        $this->addSql('CREATE INDEX idx_232b318c99e6f5df ON game (player_id)');
        $this->addSql('COMMENT ON COLUMN game.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.tweet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.reward_id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE player (id UUID NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, twitter_account_id VARCHAR(255) NOT NULL, last_play_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_98197a65322e56fb ON player (twitter_account_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_98197a65f85e0677 ON player (username)');
        $this->addSql('COMMENT ON COLUMN player.id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE reward (id UUID NOT NULL, lot_id UUID DEFAULT NULL, distributed BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_4ed17253a8cba5f7 ON reward (lot_id)');
        $this->addSql('COMMENT ON COLUMN reward.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN reward.lot_id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE media_object (id UUID NOT NULL, name VARCHAR(255) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_14d431325e237e06 ON media_object (name)');
        $this->addSql('CREATE UNIQUE INDEX uniq_14d4313282a8e361 ON media_object (file_path)');
        $this->addSql('COMMENT ON COLUMN media_object.id IS \'(DC2Type:uuid)\'');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('CREATE TABLE lot (id UUID NOT NULL, image_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, message VARCHAR(270) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_b81291b5e237e06 ON lot (name)');
        $this->addSql('CREATE INDEX idx_b81291b3da5256d ON lot (image_id)');
        $this->addSql('COMMENT ON COLUMN lot.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN lot.image_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE tweet_reply');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE twitter_account_to_follow');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE twitter_hashtag');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE tweet');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE game');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE player');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE reward');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE media_object');
        $this->abortIf(
            !$this->connection->getDatabasePlatform() instanceof \Doctrine\DBAL\Platforms\PostgreSQL100Platform,
            "Migration can only be executed safely on '\Doctrine\DBAL\Platforms\PostgreSQL100Platform'."
        );

        $this->addSql('DROP TABLE lot');
    }
}
