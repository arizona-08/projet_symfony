<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250126154712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location_vehicle (location_id INT NOT NULL, vehicle_id INT NOT NULL, PRIMARY KEY(location_id, vehicle_id))');
        $this->addSql('CREATE INDEX IDX_F5C5F72264D218E ON location_vehicle (location_id)');
        $this->addSql('CREATE INDEX IDX_F5C5F722545317D1 ON location_vehicle (vehicle_id)');
        $this->addSql('CREATE TABLE status (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE location_vehicle ADD CONSTRAINT FK_F5C5F72264D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location_vehicle ADD CONSTRAINT FK_F5C5F722545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT fk_1b80e48664d218e');
        $this->addSql('DROP INDEX idx_1b80e48664d218e');
        $this->addSql('ALTER TABLE vehicle RENAME COLUMN location_id TO status_id');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4866BF700BD FOREIGN KEY (status_id) REFERENCES status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1B80E4866BF700BD ON vehicle (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E4866BF700BD');
        $this->addSql('ALTER TABLE location_vehicle DROP CONSTRAINT FK_F5C5F72264D218E');
        $this->addSql('ALTER TABLE location_vehicle DROP CONSTRAINT FK_F5C5F722545317D1');
        $this->addSql('DROP TABLE location_vehicle');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP INDEX IDX_1B80E4866BF700BD');
        $this->addSql('ALTER TABLE vehicle RENAME COLUMN status_id TO location_id');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT fk_1b80e48664d218e FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1b80e48664d218e ON vehicle (location_id)');
    }
}
