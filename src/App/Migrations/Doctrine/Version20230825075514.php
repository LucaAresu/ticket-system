<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825075514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `tickets`(
            id VARCHAR(36) NOT NULL COMMENT \'(DC2Type:guid )\',
            title VARCHAR(128) NOT NULL,
            content VARCHAR(2048) NOT NULL,
            status VARCHAR(32) NOT NULL,
            priority VARCHAR(32) NOT NULL,
            category VARCHAR(32) NOT NULL,
            opener VARCHAR(36) NOT NULL REFERENCES `users`(id),
            operator VARCHAR(36) NULL REFERENCES `users`(id),
            expiration DATETIME NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL,
            PRIMARY KEY(id)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `tickets`');
    }
}
