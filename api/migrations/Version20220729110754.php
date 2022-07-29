<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729110754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_232b318c3d660a3b');
        $this->addSql('DROP INDEX uniq_232b318c4ed17253');
        $this->addSql('ALTER TABLE game ADD tweet_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD reward_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP reward');
        $this->addSql('ALTER TABLE game DROP tweet');
        $this->addSql('COMMENT ON COLUMN game.tweet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.reward_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE466ACA1 FOREIGN KEY (reward_id) REFERENCES reward (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C1041E39B ON game (tweet_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318CE466ACA1 ON game (reward_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C1041E39B');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CE466ACA1');
        $this->addSql('DROP INDEX UNIQ_232B318C1041E39B');
        $this->addSql('DROP INDEX UNIQ_232B318CE466ACA1');
        $this->addSql('ALTER TABLE game ADD reward VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game ADD tweet VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game DROP tweet_id');
        $this->addSql('ALTER TABLE game DROP reward_id');
        $this->addSql('CREATE UNIQUE INDEX uniq_232b318c3d660a3b ON game (tweet)');
        $this->addSql('CREATE UNIQUE INDEX uniq_232b318c4ed17253 ON game (reward)');
    }
}
