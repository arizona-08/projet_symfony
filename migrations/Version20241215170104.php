<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241215170104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE motorcycle (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DBF396750 FOREIGN KEY (id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE motorcycle ADD CONSTRAINT FK_21E380E1BF396750 FOREIGN KEY (id) REFERENCES vehicle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE vehicle ADD discr VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE car DROP CONSTRAINT FK_773DE69DBF396750');
        $this->addSql('ALTER TABLE motorcycle DROP CONSTRAINT FK_21E380E1BF396750');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE motorcycle');
        $this->addSql('ALTER TABLE vehicle DROP discr');
    }
}
