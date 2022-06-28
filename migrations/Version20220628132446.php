<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220628132446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "game_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "game" (id UUID NOT NULL, tweet INT DEFAULT NULL, url VARCHAR(255) NOT NULL, score INT DEFAULT NULL, creation_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_232B318C3D660A3B ON "game" (tweet)');
        $this->addSql('COMMENT ON COLUMN "game".id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "game" ADD CONSTRAINT FK_232B318C3D660A3B FOREIGN KEY (tweet) REFERENCES "tweet" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reward ADD game UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE reward DROP tweet');
        $this->addSql('COMMENT ON COLUMN reward.game IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT FK_4ED17253B81291B FOREIGN KEY (lot) REFERENCES "lot" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reward ADD CONSTRAINT FK_4ED17253232B318C FOREIGN KEY (game) REFERENCES "game" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_4ED17253B81291B ON reward (lot)');
        $this->addSql('CREATE INDEX IDX_4ED17253232B318C ON reward (game)');
        $this->addSql('ALTER TABLE tweet DROP tweet_date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "reward" DROP CONSTRAINT FK_4ED17253232B318C');
        $this->addSql('DROP SEQUENCE "game_id_seq" CASCADE');
        $this->addSql('DROP TABLE "game"');
        $this->addSql('ALTER TABLE "reward" DROP CONSTRAINT FK_4ED17253B81291B');
        $this->addSql('DROP INDEX IDX_4ED17253B81291B');
        $this->addSql('DROP INDEX IDX_4ED17253232B318C');
        $this->addSql('ALTER TABLE "reward" ADD tweet VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "reward" DROP game');
        $this->addSql('ALTER TABLE "tweet" ADD tweet_date DATE NOT NULL');
    }
}
