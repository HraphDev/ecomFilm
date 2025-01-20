<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119003543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartoon DROP image');
        $this->addSql('ALTER TABLE film DROP image');
        $this->addSql('ALTER TABLE series DROP image');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartoon ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE film ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE series ADD image VARCHAR(255) NOT NULL');
    }
}
