<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241229213406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE equipment_id_seq CASCADE');
        $this->addSql('CREATE TABLE kit (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, accessory JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE config_equipment DROP CONSTRAINT fk_b2eee2be24db0683');
        $this->addSql('ALTER TABLE config_equipment DROP CONSTRAINT fk_b2eee2be517fe9fe');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE config_equipment');
        $this->addSql('DROP INDEX idx_d48a2f7c545317d1');
        $this->addSql('ALTER TABLE config ADD kit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C3A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D48A2F7C545317D1 ON config (vehicle_id)');
        $this->addSql('CREATE INDEX IDX_D48A2F7C3A8E60EF ON config (kit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C3A8E60EF');
        $this->addSql('CREATE SEQUENCE equipment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE equipment (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE config_equipment (config_id INT NOT NULL, equipment_id INT NOT NULL, PRIMARY KEY(config_id, equipment_id))');
        $this->addSql('CREATE INDEX idx_b2eee2be517fe9fe ON config_equipment (equipment_id)');
        $this->addSql('CREATE INDEX idx_b2eee2be24db0683 ON config_equipment (config_id)');
        $this->addSql('ALTER TABLE config_equipment ADD CONSTRAINT fk_b2eee2be24db0683 FOREIGN KEY (config_id) REFERENCES config (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config_equipment ADD CONSTRAINT fk_b2eee2be517fe9fe FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE kit');
        $this->addSql('DROP INDEX UNIQ_D48A2F7C545317D1');
        $this->addSql('DROP INDEX IDX_D48A2F7C3A8E60EF');
        $this->addSql('ALTER TABLE config DROP kit_id');
        $this->addSql('CREATE INDEX idx_d48a2f7c545317d1 ON config (vehicle_id)');
    }
}
