<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240709120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial migration with current schema';
    }

    public function up(Schema $schema): void
    {
        // This space is intentionally left blank as the schema is already up-to-date
    }

    public function down(Schema $schema): void
    {
        // You can define how to revert the migration here, if necessary
    }
}