<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815104719 extends AbstractMigration
{
    private ContainerInterface $container;

    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            'CREATE TABLE `users` (
            id VARCHAR(36) NOT NULL COMMENT \'(DC2Type:guid )\',
            email VARCHAR(100) NOT NULL,
            name VARCHAR(64) NOT NULL,
            lastname VARCHAR(64) NOT NULL,
            password VARCHAR(100) NOT NULL,
            role VARCHAR(64) NOT NULL,
            PRIMARY KEY (id)
        )'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `users`');
    }
}
