<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629082005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE media_object_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE media_object (id INT NOT NULL, file_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE lot ADD image INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lot DROP picture_url');
        $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291BC53D045F FOREIGN KEY (image) REFERENCES media_object (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B81291BC53D045F ON lot (image)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "lot" DROP CONSTRAINT FK_B81291BC53D045F');
        $this->addSql('DROP SEQUENCE media_object_id_seq CASCADE');
        $this->addSql('DROP TABLE media_object');
        $this->addSql('DROP INDEX IDX_B81291BC53D045F');
        $this->addSql('ALTER TABLE "lot" ADD picture_url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "lot" DROP image');
    }
}
