<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


final class Version20250115112857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE accessory (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE agency (id SERIAL NOT NULL, user_id INT NOT NULL, label VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, zip_code INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_70C0C6E6A76ED395 ON agency (user_id)');
        $this->addSql('CREATE TABLE car (id INT NOT NULL, four_wheel BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE config (id SERIAL NOT NULL, client_id INT DEFAULT NULL, vehicle_id INT NOT NULL, kit_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D48A2F7C19EB6921 ON config (client_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D48A2F7C545317D1 ON config (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_D48A2F7C3A8E60EF ON config (kit_id)');
        $this->addSql('CREATE TABLE feedback (id SERIAL NOT NULL, location_id INT NOT NULL, client_id INT DEFAULT NULL, rating INT NOT NULL, comment TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D229445864D218E ON feedback (location_id)');
        $this->addSql('CREATE INDEX IDX_D229445819EB6921 ON feedback (client_id)');
        $this->addSql('COMMENT ON COLUMN feedback.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE kit (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE kit_accessory (kit_id INT NOT NULL, accessory_id INT NOT NULL, PRIMARY KEY(kit_id, accessory_id))');
        $this->addSql('CREATE INDEX IDX_12E9B8E03A8E60EF ON kit_accessory (kit_id)');
        $this->addSql('CREATE INDEX IDX_12E9B8E027E8CC78 ON kit_accessory (accessory_id)');
        $this->addSql('CREATE TABLE location (id SERIAL NOT NULL, user_id INT NOT NULL, config_id INT DEFAULT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, vip BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9E89CBA76ED395 ON location (user_id)');
        $this->addSql('CREATE INDEX IDX_5E9E89CB24DB0683 ON location (config_id)');
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
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C3A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445864D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445819EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kit_accessory ADD CONSTRAINT FK_12E9B8E03A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kit_accessory ADD CONSTRAINT FK_12E9B8E027E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB24DB0683 FOREIGN KEY (config_id) REFERENCES config (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE motorcycle ADD CONSTRAINT FK_21E380E1BF396750 FOREIGN KEY (id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E48664D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4862ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C3A8E60EF');
        $this->addSql('ALTER TABLE config ALTER kit_id SET NOT NULL');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C3A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE agency DROP CONSTRAINT FK_70C0C6E6A76ED395');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DBF396750');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C19EB6921');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C545317D1');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C3A8E60EF');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D229445864D218E');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D229445819EB6921');
        $this->addSql('ALTER TABLE kit_accessory DROP CONSTRAINT FK_12E9B8E03A8E60EF');
        $this->addSql('ALTER TABLE kit_accessory DROP CONSTRAINT FK_12E9B8E027E8CC78');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CBA76ED395');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB24DB0683');
        $this->addSql('ALTER TABLE motorcycle DROP CONSTRAINT FK_21E380E1BF396750');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E48664D218E');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E486CDEADB2A');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E4862ADD6D8C');
        $this->addSql('DROP TABLE accessory');
        $this->addSql('DROP TABLE agency');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE kit');
        $this->addSql('DROP TABLE kit_accessory');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE motorcycle');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE config ALTER kit_id DROP NOT NULL');
    }
}
