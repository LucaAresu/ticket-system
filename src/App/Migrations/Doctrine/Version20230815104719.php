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
            role VARCHAR(64) NOT NULL
        )'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `users`');
    }

    public function postUp(Schema $schema): void
    {
        // password is password
        $this->connection->executeQuery('
        INSERT INTO ticket.users (id, email, name, lastname, password, role) VALUES (\'6a5291ff-37b7-47f8-b2db-e4b1beeaafdc\', \'user@example.net\', \'User\', \'User\', \'$2y$13$JW/iCa3/cerHyoGatHXmE.a/KMKzsSsQMa/s3L7P/Glu.K3XCPx3a\', \'USER\');
INSERT INTO ticket.users (id, email, name, lastname, password, role) VALUES (\'35cabf54-d23c-4ac4-8bb7-945c1652efe6\', \'manager@example.net\', \'Manager\', \'Manager\', \'$2y$13$drz/oerHBDrKcQJMbs6K.eHSS9OumUoEi3i3xqapIYTeiOAgwo7MK\', \'MANAGER\');
INSERT INTO ticket.users (id, email, name, lastname, password, role) VALUES (\'98109a2c-50f1-4ee2-b88a-70385dddb62b\', \'operator@example.net\', \'Operator\', \'Operator\', \'$2y$13$lZToEHu7CdlCmoGdqj.nnebLB1p0qoNyuYEh1ROLYquJYcU2qBn2.\', \'OPERATOR\');
INSERT INTO ticket.users (id, email, name, lastname, password, role) VALUES (\'b5644872-9aa6-4cd2-be9c-9a3cf673f785\', \'super-operator@example.net\', \'SuperOperator\', \'SuperOperator\', \'$2y$13$JavRz2jTGhu20eq70WEeGuLXOmw63lazTrLQxKmlvxYPJz/i/ZmG.\', \'SUPER_OPERATOR\');
        ');
    }
}
