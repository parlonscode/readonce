<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230604230236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create messenger_messages table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__messages AS SELECT id, email, body, created_at, updated_at, uuid, deleted_at FROM messages');
        $this->addSql('DROP TABLE messages');
        $this->addSql('CREATE TABLE messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , uuid BLOB NOT NULL --(DC2Type:uuid)
        , deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO messages (id, email, body, created_at, updated_at, uuid, deleted_at) SELECT id, email, body, created_at, updated_at, uuid, deleted_at FROM __temp__messages');
        $this->addSql('DROP TABLE __temp__messages');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB021E96D17F50A6 ON messages (uuid)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TEMPORARY TABLE __temp__messages AS SELECT id, uuid, email, body, created_at, updated_at, deleted_at FROM messages');
        $this->addSql('DROP TABLE messages');
        $this->addSql('CREATE TABLE messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid BLOB NOT NULL --(DC2Type:uuid)
        , email VARCHAR(255) NOT NULL, body CLOB NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO messages (id, uuid, email, body, created_at, updated_at, deleted_at) SELECT id, uuid, email, body, created_at, updated_at, deleted_at FROM __temp__messages');
        $this->addSql('DROP TABLE __temp__messages');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DB021E96D17F50A6 ON messages (uuid)');
    }
}
