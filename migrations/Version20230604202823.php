<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604202823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add uuid field to messages table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__messages AS SELECT id, email, body, created_at, updated_at FROM messages');
        $this->addSql('DROP TABLE messages');
        $this->addSql('CREATE TABLE messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , uuid BLOB NOT NULL --(DC2Type:uuid)
        )');
        $this->addSql('INSERT INTO messages (id, email, body, created_at, updated_at) SELECT id, email, body, created_at, updated_at FROM __temp__messages');
        $this->addSql('DROP TABLE __temp__messages');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB021E96D17F50A6 ON messages (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__messages AS SELECT id, email, body, created_at, updated_at FROM messages');
        $this->addSql('DROP TABLE messages');
        $this->addSql('CREATE TABLE messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO messages (id, email, body, created_at, updated_at) SELECT id, email, body, created_at, updated_at FROM __temp__messages');
        $this->addSql('DROP TABLE __temp__messages');
    }
}
