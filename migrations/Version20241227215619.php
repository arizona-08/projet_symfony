<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227215619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config (id SERIAL NOT NULL, client_id INT DEFAULT NULL, vehicle_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D48A2F7C19EB6921 ON config (client_id)');
        $this->addSql('CREATE INDEX IDX_D48A2F7C545317D1 ON config (vehicle_id)');
        $this->addSql('CREATE TABLE config_equipment (config_id INT NOT NULL, equipment_id INT NOT NULL, PRIMARY KEY(config_id, equipment_id))');
        $this->addSql('CREATE INDEX IDX_B2EEE2BE24DB0683 ON config_equipment (config_id)');
        $this->addSql('CREATE INDEX IDX_B2EEE2BE517FE9FE ON config_equipment (equipment_id)');
        $this->addSql('CREATE TABLE equipment (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C19EB6921 FOREIGN KEY (client_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config_equipment ADD CONSTRAINT FK_B2EEE2BE24DB0683 FOREIGN KEY (config_id) REFERENCES config (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE config_equipment ADD CONSTRAINT FK_B2EEE2BE517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT fk_5e9e89cba76ed395');
        $this->addSql('DROP INDEX idx_5e9e89cba76ed395');
        $this->addSql('ALTER TABLE location DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C19EB6921');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C545317D1');
        $this->addSql('ALTER TABLE config_equipment DROP CONSTRAINT FK_B2EEE2BE24DB0683');
        $this->addSql('ALTER TABLE config_equipment DROP CONSTRAINT FK_B2EEE2BE517FE9FE');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE config_equipment');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('ALTER TABLE location ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT fk_5e9e89cba76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5e9e89cba76ed395 ON location (user_id)');
    }
}
