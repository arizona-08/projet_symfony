<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216095300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agency (id SERIAL NOT NULL, user_id INT NOT NULL, label VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70C0C6E6A76ED395 ON agency (user_id)');
        $this->addSql('CREATE TABLE car (id INT NOT NULL, four_wheel BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE location (id SERIAL NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN location.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN location.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE motorcycle (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE supplier (id SERIAL NOT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN supplier.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN supplier.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_verified_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, password VARCHAR(255) NOT NULL, remember_token VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "user".email_verified_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE vehicle (id SERIAL NOT NULL, location_id INT DEFAULT NULL, agency_id INT NOT NULL, supplier_id INT DEFAULT NULL, model VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, last_maintenance DATE NOT NULL, nb_kilometrage INT NOT NULL, nb_serie VARCHAR(255) NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, vehicle_fuel_type VARCHAR(255) DEFAULT NULL, trunk INT DEFAULT NULL, dimension TEXT DEFAULT NULL, nbr_place INT DEFAULT NULL, nbr_door INT DEFAULT NULL, consumption_max NUMERIC(5, 2) DEFAULT NULL, critair INT DEFAULT NULL, hp INT DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, equipment JSON DEFAULT NULL, gear_box_type VARCHAR(255) DEFAULT NULL, year VARCHAR(255) DEFAULT NULL, vehicle_type VARCHAR(255) DEFAULT NULL, discr VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B80E48664D218E ON vehicle (location_id)');
        $this->addSql('CREATE INDEX IDX_1B80E486CDEADB2A ON vehicle (agency_id)');
        $this->addSql('CREATE INDEX IDX_1B80E4862ADD6D8C ON vehicle (supplier_id)');
        $this->addSql('ALTER TABLE agency ADD CONSTRAINT FK_70C0C6E6A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DBF396750 FOREIGN KEY (id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE motorcycle ADD CONSTRAINT FK_21E380E1BF396750 FOREIGN KEY (id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48664D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4862ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE agency DROP CONSTRAINT FK_70C0C6E6A76ED395');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DBF396750');
        $this->addSql('ALTER TABLE motorcycle DROP CONSTRAINT FK_21E380E1BF396750');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E48664D218E');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E486CDEADB2A');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E4862ADD6D8C');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE motorcycle');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE vehicle');
    }
}
