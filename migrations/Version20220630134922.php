<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220630134922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ALTER creation_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE game ALTER creation_date DROP DEFAULT');
        $this->addSql('ALTER TABLE reward ALTER date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE reward ALTER date DROP DEFAULT');
        $this->addSql('ALTER TABLE tweet ALTER creation_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE tweet ALTER creation_date DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "tweet" ALTER creation_date TYPE DATE');
        $this->addSql('ALTER TABLE "tweet" ALTER creation_date DROP DEFAULT');
        $this->addSql('ALTER TABLE "game" ALTER creation_date TYPE DATE');
        $this->addSql('ALTER TABLE "game" ALTER creation_date DROP DEFAULT');
        $this->addSql('ALTER TABLE "reward" ALTER date TYPE DATE');
        $this->addSql('ALTER TABLE "reward" ALTER date DROP DEFAULT');
    }
}
