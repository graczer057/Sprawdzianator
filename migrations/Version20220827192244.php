<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220827192244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directors DROP FOREIGN KEY FK_A6ADADC432C8A3DE');
        $this->addSql('DROP INDEX UNIQ_A6ADADC432C8A3DE ON directors');
        $this->addSql('ALTER TABLE directors CHANGE organization_id organisation_id INT NOT NULL');
        $this->addSql('ALTER TABLE directors ADD CONSTRAINT FK_A6ADADC49E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A6ADADC49E6B1585 ON directors (organisation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directors DROP FOREIGN KEY FK_A6ADADC49E6B1585');
        $this->addSql('DROP INDEX UNIQ_A6ADADC49E6B1585 ON directors');
        $this->addSql('ALTER TABLE directors CHANGE organisation_id organization_id INT NOT NULL');
        $this->addSql('ALTER TABLE directors ADD CONSTRAINT FK_A6ADADC432C8A3DE FOREIGN KEY (organization_id) REFERENCES organisations (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A6ADADC432C8A3DE ON directors (organization_id)');
    }
}
