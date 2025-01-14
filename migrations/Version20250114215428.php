<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114215428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accessory (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE kit_accessory (kit_id INT NOT NULL, accessory_id INT NOT NULL, PRIMARY KEY(kit_id, accessory_id))');
        $this->addSql('CREATE INDEX IDX_12E9B8E03A8E60EF ON kit_accessory (kit_id)');
        $this->addSql('CREATE INDEX IDX_12E9B8E027E8CC78 ON kit_accessory (accessory_id)');
        $this->addSql('ALTER TABLE kit_accessory ADD CONSTRAINT FK_12E9B8E03A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kit_accessory ADD CONSTRAINT FK_12E9B8E027E8CC78 FOREIGN KEY (accessory_id) REFERENCES accessory (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D229445864D218E');
        $this->addSql('ALTER TABLE feedback ALTER location_id SET NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445864D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE kit DROP accessory');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB24DB0683');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB24DB0683 FOREIGN KEY (config_id) REFERENCES config (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE kit_accessory DROP CONSTRAINT FK_12E9B8E03A8E60EF');
        $this->addSql('ALTER TABLE kit_accessory DROP CONSTRAINT FK_12E9B8E027E8CC78');
        $this->addSql('DROP TABLE accessory');
        $this->addSql('DROP TABLE kit_accessory');
        $this->addSql('ALTER TABLE kit ADD accessory JSON NOT NULL');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT fk_d229445864d218e');
        $this->addSql('ALTER TABLE feedback ALTER location_id DROP NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT fk_d229445864d218e FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT fk_5e9e89cb24db0683');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT fk_5e9e89cb24db0683 FOREIGN KEY (config_id) REFERENCES config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
