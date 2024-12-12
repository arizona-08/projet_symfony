<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212205135 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE supplier (id SERIAL NOT NULL, label VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN supplier.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN supplier.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE vehicle ADD supplier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD vehicle_fuel_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD trunk INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD dimension TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD nbr_place INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD nbr_door INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD consumption_max NUMERIC(5, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD critair INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD four_wheel BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD hp INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD equipment JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD gear_box_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD year VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD vehicle_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4862ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1B80E4862ADD6D8C ON vehicle (supplier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E4862ADD6D8C');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP INDEX IDX_1B80E4862ADD6D8C');
        $this->addSql('ALTER TABLE vehicle DROP supplier_id');
        $this->addSql('ALTER TABLE vehicle DROP vehicle_fuel_type');
        $this->addSql('ALTER TABLE vehicle DROP trunk');
        $this->addSql('ALTER TABLE vehicle DROP dimension');
        $this->addSql('ALTER TABLE vehicle DROP nbr_place');
        $this->addSql('ALTER TABLE vehicle DROP nbr_door');
        $this->addSql('ALTER TABLE vehicle DROP consumption_max');
        $this->addSql('ALTER TABLE vehicle DROP critair');
        $this->addSql('ALTER TABLE vehicle DROP four_wheel');
        $this->addSql('ALTER TABLE vehicle DROP hp');
        $this->addSql('ALTER TABLE vehicle DROP color');
        $this->addSql('ALTER TABLE vehicle DROP equipment');
        $this->addSql('ALTER TABLE vehicle DROP gear_box_type');
        $this->addSql('ALTER TABLE vehicle DROP year');
        $this->addSql('ALTER TABLE vehicle DROP vehicle_type');
    }
}
