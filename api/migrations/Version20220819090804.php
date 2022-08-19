<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220819090804 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id UUID NOT NULL, tweet_id UUID DEFAULT NULL, player_id UUID DEFAULT NULL, reward_id UUID DEFAULT NULL, score INT DEFAULT NULL, play_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C1041E39B ON game (tweet_id)');
        $this->addSql('CREATE INDEX IDX_232B318C99E6F5DF ON game (player_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318CE466ACA1 ON game (reward_id)');
        $this->addSql('COMMENT ON COLUMN game.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.tweet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.reward_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE lot (id UUID NOT NULL, image_id UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B81291B5E237E06 ON lot (name)');
        $this->addSql('CREATE INDEX IDX_B81291B3DA5256D ON lot (image_id)');
        $this->addSql('COMMENT ON COLUMN lot.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN lot.image_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "media_object" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14D431325E237E06 ON "media_object" (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14D4313282A8E361 ON "media_object" (file_path)');
        $this->addSql('COMMENT ON COLUMN "media_object".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE player (id UUID NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, twitter_account_id VARCHAR(255) NOT NULL, last_play_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65F85E0677 ON player (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65322E56FB ON player (twitter_account_id)');
        $this->addSql('COMMENT ON COLUMN player.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE reward (id UUID NOT NULL, lot_id UUID DEFAULT NULL, distributed BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4ED17253A8CBA5F7 ON reward (lot_id)');
        $this->addSql('COMMENT ON COLUMN reward.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN reward.lot_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE tweet (id UUID NOT NULL, player_id UUID DEFAULT NULL, tweet_id VARCHAR(255) NOT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D660A3B1041E39B ON tweet (tweet_id)');
        $this->addSql('CREATE INDEX IDX_3D660A3B99E6F5DF ON tweet (player_id)');
        $this->addSql('COMMENT ON COLUMN tweet.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN tweet.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE tweet_reply (id UUID NOT NULL, name VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_61631BAF5E237E06 ON tweet_reply (name)');
        $this->addSql('COMMENT ON COLUMN tweet_reply.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE twitter_account_to_follow (id UUID NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, twitter_account_id VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_99F3E05F85E0677 ON twitter_account_to_follow (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_99F3E05322E56FB ON twitter_account_to_follow (twitter_account_id)');
        $this->addSql('COMMENT ON COLUMN twitter_account_to_follow.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE twitter_hashtag (id UUID NOT NULL, hashtag VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96C4DEF35AB52A61 ON twitter_hashtag (hashtag)');
        $this->addSql('COMMENT ON COLUMN twitter_hashtag.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE466ACA1 FOREIGN KEY (reward_id) REFERENCES reward (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B3DA5256D FOREIGN KEY (image_id) REFERENCES "media_object" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT FK_4ED17253A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reward DROP CONSTRAINT FK_4ED17253A8CBA5F7');
        $this->addSql('ALTER TABLE lot DROP CONSTRAINT FK_B81291B3DA5256D');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C99E6F5DF');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3B99E6F5DF');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CE466ACA1');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C1041E39B');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE lot');
        $this->addSql('DROP TABLE "media_object"');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE reward');
        $this->addSql('DROP TABLE tweet');
        $this->addSql('DROP TABLE tweet_reply');
        $this->addSql('DROP TABLE twitter_account_to_follow');
        $this->addSql('DROP TABLE twitter_hashtag');
    }
}
