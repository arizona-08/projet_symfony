<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250114145044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT FK_D229445864D218E');
        $this->addSql('ALTER TABLE feedback ALTER location_id SET NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D229445864D218E FOREIGN KEY (location_id) REFERENCES location (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB24DB0683');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB24DB0683 FOREIGN KEY (config_id) REFERENCES config (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE feedback DROP CONSTRAINT fk_d229445864d218e');
        $this->addSql('ALTER TABLE feedback ALTER location_id DROP NOT NULL');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT fk_d229445864d218e FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT fk_5e9e89cb24db0683');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT fk_5e9e89cb24db0683 FOREIGN KEY (config_id) REFERENCES config (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
