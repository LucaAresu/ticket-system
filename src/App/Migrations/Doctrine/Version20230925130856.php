<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230925130856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE `answers` (
            id VARCHAR(36) NOT NULL COMMENT \'(DC2Type:guid )\',
            ticket_id VARCHAR(36) NOT NULL COMMENT \'(DC2Type:guid )\',
            user_id VARCHAR(36) NOT NULL COMMENT \'(DC2Type:guid )\',
            content VARCHAR(512) NOT NULL COMMENT \'(DC2Type:guid )\',
            created_at DATETIME NOT NULL,
            PRIMARY KEY(id),
            CONSTRAINT answers_ticket_id FOREIGN KEY(`ticket_id`) REFERENCES `tickets`(`id`),
            CONSTRAINT answers_user_id FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `answers` DROP CONSTRAINT answers_ticket_id');
        // $this->addSql('ALTER TABLE `answers` DROP CONSTRAINT answers_user_id');
        $this->addSql('DROP TABLE `answers`');
    }
}
