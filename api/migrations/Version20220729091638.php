<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729091638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318ce466aca1');
        $this->addSql('DROP INDEX uniq_232b318ce466aca1');
        $this->addSql('ALTER TABLE game ADD reward VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game DROP reward_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C4ED17253 ON game (reward)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_232B318C4ED17253');
        $this->addSql('ALTER TABLE game ADD reward_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP reward');
        $this->addSql('COMMENT ON COLUMN game.reward_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318ce466aca1 FOREIGN KEY (reward_id) REFERENCES reward (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_232b318ce466aca1 ON game (reward_id)');
    }
}
