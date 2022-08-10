<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810075814 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_99f3e0533e79199');
        $this->addSql('ALTER TABLE twitter_account_to_follow ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE twitter_account_to_follow ADD username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE twitter_account_to_follow DROP twitter_account_name');
        $this->addSql('ALTER TABLE twitter_account_to_follow DROP twitter_account_username');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_99F3E05F85E0677 ON twitter_account_to_follow (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_99F3E05F85E0677');
        $this->addSql('ALTER TABLE twitter_account_to_follow ADD twitter_account_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE twitter_account_to_follow ADD twitter_account_username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE twitter_account_to_follow DROP name');
        $this->addSql('ALTER TABLE twitter_account_to_follow DROP username');
        $this->addSql('CREATE UNIQUE INDEX uniq_99f3e0533e79199 ON twitter_account_to_follow (twitter_account_username)');
    }
}
