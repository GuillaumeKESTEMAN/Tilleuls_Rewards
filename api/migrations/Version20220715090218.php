<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220715090218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "game" (id UUID NOT NULL, tweet UUID NOT NULL, player UUID NOT NULL, url VARCHAR(255) NOT NULL, score INT DEFAULT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, play_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_232B318C3D660A3B ON "game" (tweet)');
        $this->addSql('CREATE INDEX IDX_232B318C98197A65 ON "game" (player)');
        $this->addSql('COMMENT ON COLUMN "game".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "game".tweet IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "game".player IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "lot" (id UUID NOT NULL, image UUID DEFAULT NULL, name VARCHAR(255) NOT NULL, quantity INT NOT NULL, message VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B81291BC53D045F ON "lot" (image)');
        $this->addSql('COMMENT ON COLUMN "lot".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "lot".image IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "media_object" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "media_object".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "player" (id UUID NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, twitter_account_id VARCHAR(255) NOT NULL, last_play_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "player".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "reward" (id UUID NOT NULL, lot UUID DEFAULT NULL, game UUID NOT NULL, win_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, distributed BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4ED17253B81291B ON "reward" (lot)');
        $this->addSql('CREATE INDEX IDX_4ED17253232B318C ON "reward" (game)');
        $this->addSql('COMMENT ON COLUMN "reward".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "reward".lot IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "reward".game IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "tweet" (id UUID NOT NULL, player UUID NOT NULL, tweet_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3D660A3B98197A65 ON "tweet" (player)');
        $this->addSql('COMMENT ON COLUMN "tweet".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "tweet".player IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "twitter_account_to_follow" (id UUID NOT NULL, twitter_account_name VARCHAR(255) NOT NULL, twitter_account_username VARCHAR(255) NOT NULL, twitter_account_id VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "twitter_account_to_follow".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE "twitter_hashtag" (id UUID NOT NULL, hashtag VARCHAR(255) NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "twitter_hashtag".id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "game" ADD CONSTRAINT FK_232B318C3D660A3B FOREIGN KEY (tweet) REFERENCES "tweet" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "game" ADD CONSTRAINT FK_232B318C98197A65 FOREIGN KEY (player) REFERENCES "player" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "lot" ADD CONSTRAINT FK_B81291BC53D045F FOREIGN KEY (image) REFERENCES "media_object" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "reward" ADD CONSTRAINT FK_4ED17253B81291B FOREIGN KEY (lot) REFERENCES "lot" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "reward" ADD CONSTRAINT FK_4ED17253232B318C FOREIGN KEY (game) REFERENCES "game" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "tweet" ADD CONSTRAINT FK_3D660A3B98197A65 FOREIGN KEY (player) REFERENCES "player" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "reward" DROP CONSTRAINT FK_4ED17253232B318C');
        $this->addSql('ALTER TABLE "reward" DROP CONSTRAINT FK_4ED17253B81291B');
        $this->addSql('ALTER TABLE "lot" DROP CONSTRAINT FK_B81291BC53D045F');
        $this->addSql('ALTER TABLE "game" DROP CONSTRAINT FK_232B318C98197A65');
        $this->addSql('ALTER TABLE "tweet" DROP CONSTRAINT FK_3D660A3B98197A65');
        $this->addSql('ALTER TABLE "game" DROP CONSTRAINT FK_232B318C3D660A3B');
        $this->addSql('DROP TABLE "game"');
        $this->addSql('DROP TABLE "lot"');
        $this->addSql('DROP TABLE "media_object"');
        $this->addSql('DROP TABLE "player"');
        $this->addSql('DROP TABLE "reward"');
        $this->addSql('DROP TABLE "tweet"');
        $this->addSql('DROP TABLE "twitter_account_to_follow"');
        $this->addSql('DROP TABLE "twitter_hashtag"');
    }
}
