<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119004739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE classical_film (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, release_date DATE NOT NULL, price DOUBLE PRECISION NOT NULL, image_path VARCHAR(255) DEFAULT NULL, video_path VARCHAR(255) DEFAULT NULL, category VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cartoon ADD image_path VARCHAR(255) DEFAULT NULL, ADD video_path VARCHAR(255) DEFAULT NULL, ADD category VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE film ADD image_path VARCHAR(255) DEFAULT NULL, ADD video_path VARCHAR(255) DEFAULT NULL, ADD category VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE series ADD image_path VARCHAR(255) DEFAULT NULL, ADD video_path VARCHAR(255) DEFAULT NULL, ADD category VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE classical_film');
        $this->addSql('ALTER TABLE cartoon DROP image_path, DROP video_path, DROP category');
        $this->addSql('ALTER TABLE film DROP image_path, DROP video_path, DROP category');
        $this->addSql('ALTER TABLE series DROP image_path, DROP video_path, DROP category');
    }
}
