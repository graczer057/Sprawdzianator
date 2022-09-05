<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831194646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exams (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, organisation_id INT NOT NULL, class_id INT NOT NULL, title VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, active_date DATETIME NOT NULL, INDEX IDX_6931132841807E1D (teacher_id), INDEX IDX_693113289E6B1585 (organisation_id), INDEX IDX_69311328EA000B10 (class_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exams ADD CONSTRAINT FK_6931132841807E1D FOREIGN KEY (teacher_id) REFERENCES teachers (id)');
        $this->addSql('ALTER TABLE exams ADD CONSTRAINT FK_693113289E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisations (id)');
        $this->addSql('ALTER TABLE exams ADD CONSTRAINT FK_69311328EA000B10 FOREIGN KEY (class_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE excersises ADD exam_id INT NOT NULL, CHANGE grade grade INT DEFAULT NULL');
        $this->addSql('ALTER TABLE excersises ADD CONSTRAINT FK_2152E5A4578D5E91 FOREIGN KEY (exam_id) REFERENCES exams (id)');
        $this->addSql('CREATE INDEX IDX_2152E5A4578D5E91 ON excersises (exam_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE excersises DROP FOREIGN KEY FK_2152E5A4578D5E91');
        $this->addSql('ALTER TABLE exams DROP FOREIGN KEY FK_6931132841807E1D');
        $this->addSql('ALTER TABLE exams DROP FOREIGN KEY FK_693113289E6B1585');
        $this->addSql('ALTER TABLE exams DROP FOREIGN KEY FK_69311328EA000B10');
        $this->addSql('DROP TABLE exams');
        $this->addSql('DROP INDEX IDX_2152E5A4578D5E91 ON excersises');
        $this->addSql('ALTER TABLE excersises DROP exam_id, CHANGE grade grade INT NOT NULL');
    }
}
