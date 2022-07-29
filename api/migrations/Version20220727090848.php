<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727090848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318c3d660a3b');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT fk_232b318c98197a65');
        $this->addSql('DROP INDEX idx_232b318c98197a65');
        $this->addSql('DROP INDEX uniq_232b318c3d660a3b');
        $this->addSql('ALTER TABLE game ALTER tweet TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE game ALTER tweet DROP DEFAULT');
        $this->addSql('ALTER TABLE game ALTER player TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE game ALTER player DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN game.tweet IS NULL');
        $this->addSql('COMMENT ON COLUMN game.player IS NULL');
        $this->addSql('ALTER TABLE reward DROP CONSTRAINT fk_4ed17253232b318c');
        $this->addSql('ALTER TABLE reward ALTER game TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE reward ALTER game DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN reward.game IS NULL');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT fk_3d660a3b98197a65');
        $this->addSql('DROP INDEX idx_3d660a3b98197a65');
        $this->addSql('ALTER TABLE tweet ALTER player TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE tweet ALTER player DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN tweet.player IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tweet ALTER player TYPE UUID');
        $this->addSql('ALTER TABLE tweet ALTER player DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN tweet.player IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT fk_3d660a3b98197a65 FOREIGN KEY (player) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_3d660a3b98197a65 ON tweet (player)');
        $this->addSql('ALTER TABLE game ALTER tweet TYPE UUID');
        $this->addSql('ALTER TABLE game ALTER tweet DROP DEFAULT');
        $this->addSql('ALTER TABLE game ALTER player TYPE UUID');
        $this->addSql('ALTER TABLE game ALTER player DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN game.tweet IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.player IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318c3d660a3b FOREIGN KEY (tweet) REFERENCES tweet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT fk_232b318c98197a65 FOREIGN KEY (player) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_232b318c98197a65 ON game (player)');
        $this->addSql('CREATE UNIQUE INDEX uniq_232b318c3d660a3b ON game (tweet)');
        $this->addSql('ALTER TABLE reward ALTER game TYPE UUID');
        $this->addSql('ALTER TABLE reward ALTER game DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN reward.game IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT fk_4ed17253232b318c FOREIGN KEY (game) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
