<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901195903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student_exams (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, exam_id INT NOT NULL, exercises_id INT NOT NULL, INDEX IDX_E1066AB5CB944F1A (student_id), INDEX IDX_E1066AB5578D5E91 (exam_id), INDEX IDX_E1066AB51AFA70CA (exercises_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_pictures (id INT AUTO_INCREMENT NOT NULL, student_exams_id INT NOT NULL, INDEX IDX_3CF7635BD3A5DF32 (student_exams_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student_exams ADD CONSTRAINT FK_E1066AB5CB944F1A FOREIGN KEY (student_id) REFERENCES students (id)');
        $this->addSql('ALTER TABLE student_exams ADD CONSTRAINT FK_E1066AB5578D5E91 FOREIGN KEY (exam_id) REFERENCES exams (id)');
        $this->addSql('ALTER TABLE student_exams ADD CONSTRAINT FK_E1066AB51AFA70CA FOREIGN KEY (exercises_id) REFERENCES excersises (id)');
        $this->addSql('ALTER TABLE student_pictures ADD CONSTRAINT FK_3CF7635BD3A5DF32 FOREIGN KEY (student_exams_id) REFERENCES student_exams (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_exams DROP FOREIGN KEY FK_E1066AB5CB944F1A');
        $this->addSql('ALTER TABLE student_exams DROP FOREIGN KEY FK_E1066AB5578D5E91');
        $this->addSql('ALTER TABLE student_exams DROP FOREIGN KEY FK_E1066AB51AFA70CA');
        $this->addSql('ALTER TABLE student_pictures DROP FOREIGN KEY FK_3CF7635BD3A5DF32');
        $this->addSql('DROP TABLE student_exams');
        $this->addSql('DROP TABLE student_pictures');
    }
}
