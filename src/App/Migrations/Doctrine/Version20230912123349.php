<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912123349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
        CREATE TABLE `operators` (
            id VARCHAR(36) NOT NULL COMMENT \'(DC2Type:guid )\',
            user_id VARCHAR(36) NOT NULL COMMENT \'(DC2Type:guid )\',
            category VARCHAR(32) NULL,
            PRIMARY KEY(id),
            CONSTRAINT operators_user_id FOREIGN KEY(`user_id`) REFERENCES `users`(`id`)
        )
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE `operators` DROP FOREIGN KEY operators_user_id');
        $this->addSql('DROP TABLE `operators`');
    }
}
