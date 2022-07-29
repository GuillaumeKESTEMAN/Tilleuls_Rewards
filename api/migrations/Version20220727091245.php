<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727091245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD tweet_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD player_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE game DROP tweet');
        $this->addSql('ALTER TABLE game DROP player');
        $this->addSql('COMMENT ON COLUMN game.tweet_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN game.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C1041E39B FOREIGN KEY (tweet_id) REFERENCES tweet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C1041E39B ON game (tweet_id)');
        $this->addSql('CREATE INDEX IDX_232B318C99E6F5DF ON game (player_id)');
        $this->addSql('ALTER TABLE reward DROP CONSTRAINT fk_4ed17253b81291b');
        $this->addSql('DROP INDEX uniq_4ed17253232b318c');
        $this->addSql('DROP INDEX idx_4ed17253b81291b');
        $this->addSql('ALTER TABLE reward ADD game_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE reward DROP game');
        $this->addSql('ALTER TABLE reward RENAME COLUMN lot TO lot_id');
        $this->addSql('COMMENT ON COLUMN reward.game_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT FK_4ED17253A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT FK_4ED17253E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4ED17253A8CBA5F7 ON reward (lot_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4ED17253E48FD905 ON reward (game_id)');
        $this->addSql('ALTER TABLE tweet ADD player_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE tweet DROP player');
        $this->addSql('COMMENT ON COLUMN tweet.player_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE tweet ADD CONSTRAINT FK_3D660A3B99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3D660A3B99E6F5DF ON tweet (player_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C1041E39B');
        $this->addSql('ALTER TABLE game DROP CONSTRAINT FK_232B318C99E6F5DF');
        $this->addSql('DROP INDEX UNIQ_232B318C1041E39B');
        $this->addSql('DROP INDEX IDX_232B318C99E6F5DF');
        $this->addSql('ALTER TABLE game ADD tweet VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game ADD player VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game DROP tweet_id');
        $this->addSql('ALTER TABLE game DROP player_id');
        $this->addSql('ALTER TABLE reward DROP CONSTRAINT FK_4ED17253A8CBA5F7');
        $this->addSql('ALTER TABLE reward DROP CONSTRAINT FK_4ED17253E48FD905');
        $this->addSql('DROP INDEX IDX_4ED17253A8CBA5F7');
        $this->addSql('DROP INDEX UNIQ_4ED17253E48FD905');
        $this->addSql('ALTER TABLE reward ADD lot UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE reward ADD game VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reward DROP lot_id');
        $this->addSql('ALTER TABLE reward DROP game_id');
        $this->addSql('COMMENT ON COLUMN reward.lot IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT fk_4ed17253b81291b FOREIGN KEY (lot) REFERENCES lot (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_4ed17253232b318c ON reward (game)');
        $this->addSql('CREATE INDEX idx_4ed17253b81291b ON reward (lot)');
        $this->addSql('ALTER TABLE tweet DROP CONSTRAINT FK_3D660A3B99E6F5DF');
        $this->addSql('DROP INDEX IDX_3D660A3B99E6F5DF');
        $this->addSql('ALTER TABLE tweet ADD player VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tweet DROP player_id');
    }
}
