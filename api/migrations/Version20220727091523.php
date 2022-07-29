<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727091523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lot DROP CONSTRAINT fk_b81291bc53d045f');
        $this->addSql('DROP INDEX idx_b81291bc53d045f');
        $this->addSql('ALTER TABLE lot RENAME COLUMN image TO image_id');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B3DA5256D FOREIGN KEY (image_id) REFERENCES "media_object" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B81291B3DA5256D ON lot (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lot DROP CONSTRAINT FK_B81291B3DA5256D');
        $this->addSql('DROP INDEX IDX_B81291B3DA5256D');
        $this->addSql('ALTER TABLE lot RENAME COLUMN image_id TO image');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT fk_b81291bc53d045f FOREIGN KEY (image) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b81291bc53d045f ON lot (image)');
    }
}
