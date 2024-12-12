<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210200403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location (id SERIAL NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN location.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN location.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "user" ADD roles JSON NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".email_verified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE vehicle (id SERIAL NOT NULL, location_id INT DEFAULT NULL, model VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, last_maintenance DATE NOT NULL, nb_kilometrage INT NOT NULL, nb_serie VARCHAR(255) NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B80E48664D218E ON vehicle (location_id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48664D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E48664D218E');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE vehicle');
    }
}
