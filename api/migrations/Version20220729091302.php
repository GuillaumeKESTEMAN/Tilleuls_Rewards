<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220729091302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14D431325E237E06 ON media_object (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_14D4313282A8E361 ON media_object (file_path)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65F85E0677 ON player (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A65322E56FB ON player (twitter_account_id)');
        $this->addSql('ALTER TABLE reward DROP win_date');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D660A3B1041E39B ON tweet (tweet_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_61631BAF5E237E06 ON tweet_reply (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_99F3E0533E79199 ON twitter_account_to_follow (twitter_account_username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_99F3E05322E56FB ON twitter_account_to_follow (twitter_account_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_96C4DEF35AB52A61 ON twitter_hashtag (hashtag)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_14D431325E237E06');
        $this->addSql('DROP INDEX UNIQ_14D4313282A8E361');
        $this->addSql('DROP INDEX UNIQ_96C4DEF35AB52A61');
        $this->addSql('DROP INDEX UNIQ_98197A65F85E0677');
        $this->addSql('DROP INDEX UNIQ_98197A65322E56FB');
        $this->addSql('DROP INDEX UNIQ_99F3E0533E79199');
        $this->addSql('DROP INDEX UNIQ_99F3E05322E56FB');
        $this->addSql('DROP INDEX UNIQ_61631BAF5E237E06');
        $this->addSql('ALTER TABLE reward ADD win_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('DROP INDEX UNIQ_3D660A3B1041E39B');
    }
}
