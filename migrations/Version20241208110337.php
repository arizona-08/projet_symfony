<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241208110337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".email_verified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE vehicle (id SERIAL NOT NULL, model VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, last_maintenance DATE NOT NULL, nb_kilometrage INT NOT NULL, nb_serie VARCHAR(255) NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE vehicle');
    }
}
