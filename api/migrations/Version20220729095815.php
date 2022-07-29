<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729095815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318c1041e39b');
        $this->addSql('DROP INDEX uniq_232b318c1041e39b');
        $this->addSql('ALTER TABLE game ADD tweet VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game DROP tweet_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C3D660A3B ON game (tweet)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_232B318C3D660A3B');
        $this->addSql('ALTER TABLE game ADD tweet_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP tweet');
        $this->addSql('COMMENT ON COLUMN game.tweet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318c1041e39b FOREIGN KEY (tweet_id) REFERENCES tweet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_232b318c1041e39b ON game (tweet_id)');
    }
}
