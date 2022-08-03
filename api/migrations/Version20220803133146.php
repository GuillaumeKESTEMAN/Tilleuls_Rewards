<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803133146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lot DROP CONSTRAINT FK_B81291B3DA5256D');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B3DA5256D FOREIGN KEY (image_id) REFERENCES "media_object" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lot DROP CONSTRAINT fk_b81291b3da5256d');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT fk_b81291b3da5256d FOREIGN KEY (image_id) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
