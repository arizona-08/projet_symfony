<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241211211146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicle ADD agency_id INT NOT NULL');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486CDEADB2A FOREIGN KEY (agency_id) REFERENCES agency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1B80E486CDEADB2A ON vehicle (agency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicle DROP CONSTRAINT FK_1B80E486CDEADB2A');
        $this->addSql('DROP INDEX IDX_1B80E486CDEADB2A');
        $this->addSql('ALTER TABLE vehicle DROP agency_id');
    }
}
