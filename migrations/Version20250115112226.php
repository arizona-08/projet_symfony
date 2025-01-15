<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250115112226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE config DROP CONSTRAINT FK_D48A2F7C3A8E60EF');
        $this->addSql('ALTER TABLE config ALTER kit_id SET NOT NULL');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT FK_D48A2F7C3A8E60EF FOREIGN KEY (kit_id) REFERENCES kit (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE config DROP CONSTRAINT fk_d48a2f7c3a8e60ef');
        $this->addSql('ALTER TABLE config ALTER kit_id DROP NOT NULL');
        $this->addSql('ALTER TABLE config ADD CONSTRAINT fk_d48a2f7c3a8e60ef FOREIGN KEY (kit_id) REFERENCES kit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
