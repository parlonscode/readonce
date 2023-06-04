<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604185423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add created_at and updated_at fields to messages table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE messages ADD COLUMN created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE messages ADD COLUMN updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__messages AS SELECT id, email, body FROM messages');
        $this->addSql('DROP TABLE messages');
        $this->addSql('CREATE TABLE messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, body CLOB NOT NULL)');
        $this->addSql('INSERT INTO messages (id, email, body) SELECT id, email, body FROM __temp__messages');
        $this->addSql('DROP TABLE __temp__messages');
    }
}
