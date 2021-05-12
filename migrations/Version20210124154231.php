<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210124154231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E91E5C07DC');
        $this->addSql('DROP INDEX IDX_1483A5E91E5C07DC ON users');
        $this->addSql('ALTER TABLE users DROP excersises_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD excersises_id INT NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E91E5C07DC FOREIGN KEY (excersises_id) REFERENCES excersises (id)');
        $this->addSql('CREATE INDEX IDX_1483A5E91E5C07DC ON users (excersises_id)');
    }
}
