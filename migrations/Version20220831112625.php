<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831112625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE students (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, organisation_id INT NOT NULL, class_id INT NOT NULL, UNIQUE INDEX UNIQ_A4698DB2A76ED395 (user_id), INDEX IDX_A4698DB29E6B1585 (organisation_id), INDEX IDX_A4698DB2EA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB2A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB29E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisations (id)');
        $this->addSql('ALTER TABLE students ADD CONSTRAINT FK_A4698DB2EA000B10 FOREIGN KEY (class_id) REFERENCES classes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2A76ED395');
        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB29E6B1585');
        $this->addSql('ALTER TABLE students DROP FOREIGN KEY FK_A4698DB2EA000B10');
        $this->addSql('DROP TABLE students');
    }
}
