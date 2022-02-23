<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220211114040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `email` and `password` fields to user table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user ADD email VARCHAR(100) NOT NULL, ADD password VARCHAR(100) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX U_user_email ON user (email)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX U_user_email ON `user`');
        $this->addSql('ALTER TABLE `user` DROP email, DROP password');
    }
}
