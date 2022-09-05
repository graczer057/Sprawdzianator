<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220828123245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teachers ADD organisation_id INT NOT NULL, DROP object');
        $this->addSql('ALTER TABLE teachers ADD CONSTRAINT FK_ED071FF69E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ED071FF69E6B1585 ON teachers (organisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE teachers DROP FOREIGN KEY FK_ED071FF69E6B1585');
        $this->addSql('DROP INDEX UNIQ_ED071FF69E6B1585 ON teachers');
        $this->addSql('ALTER TABLE teachers ADD object VARCHAR(255) NOT NULL, DROP organisation_id');
    }
}
