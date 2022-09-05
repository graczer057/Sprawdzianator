<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826155208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE directors (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, organization_id INT NOT NULL, UNIQUE INDEX UNIQ_A6ADADC4A76ED395 (user_id), UNIQUE INDEX UNIQ_A6ADADC432C8A3DE (organization_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, verified TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teachers (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, director_id INT NOT NULL, object VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_ED071FF6A76ED395 (user_id), INDEX IDX_ED071FF6899FB366 (director_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE directors ADD CONSTRAINT FK_A6ADADC4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE directors ADD CONSTRAINT FK_A6ADADC432C8A3DE FOREIGN KEY (organization_id) REFERENCES organisations (id)');
        $this->addSql('ALTER TABLE teachers ADD CONSTRAINT FK_ED071FF6A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE teachers ADD CONSTRAINT FK_ED071FF6899FB366 FOREIGN KEY (director_id) REFERENCES directors (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE directors DROP FOREIGN KEY FK_A6ADADC4A76ED395');
        $this->addSql('ALTER TABLE directors DROP FOREIGN KEY FK_A6ADADC432C8A3DE');
        $this->addSql('ALTER TABLE teachers DROP FOREIGN KEY FK_ED071FF6A76ED395');
        $this->addSql('ALTER TABLE teachers DROP FOREIGN KEY FK_ED071FF6899FB366');
        $this->addSql('DROP TABLE directors');
        $this->addSql('DROP TABLE organisations');
        $this->addSql('DROP TABLE teachers');
    }
}
