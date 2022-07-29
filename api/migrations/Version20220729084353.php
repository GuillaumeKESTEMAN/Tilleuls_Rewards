<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729084353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD reward_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN game.reward_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CE466ACA1 FOREIGN KEY (reward_id) REFERENCES reward (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318CE466ACA1 ON game (reward_id)');
        $this->addSql('ALTER TABLE reward DROP CONSTRAINT fk_4ed17253e48fd905');
        $this->addSql('DROP INDEX uniq_4ed17253e48fd905');
        $this->addSql('ALTER TABLE reward DROP game_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318CE466ACA1');
        $this->addSql('DROP INDEX UNIQ_232B318CE466ACA1');
        $this->addSql('ALTER TABLE game DROP reward_id');
        $this->addSql('ALTER TABLE reward ADD game_id UUID DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN reward.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT fk_4ed17253e48fd905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_4ed17253e48fd905 ON reward (game_id)');
    }
}
