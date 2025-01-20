<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119232930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartoon_actor DROP FOREIGN KEY FK_7841CEB9C429E136');
        $this->addSql('ALTER TABLE cartoon_actor DROP FOREIGN KEY FK_7841CEB910DAF24A');
        $this->addSql('ALTER TABLE classicalfilm_actor DROP FOREIGN KEY FK_BA346D6DA2AC6F6E');
        $this->addSql('ALTER TABLE classicalfilm_actor DROP FOREIGN KEY FK_BA346D6D10DAF24A');
        $this->addSql('ALTER TABLE film_actor DROP FOREIGN KEY FK_DD19A8A9567F5183');
        $this->addSql('ALTER TABLE film_actor DROP FOREIGN KEY FK_DD19A8A910DAF24A');
        $this->addSql('ALTER TABLE series_actor DROP FOREIGN KEY FK_31FAB2E410DAF24A');
        $this->addSql('ALTER TABLE series_actor DROP FOREIGN KEY FK_31FAB2E45278319C');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE cartoon_actor');
        $this->addSql('DROP TABLE classicalfilm_actor');
        $this->addSql('DROP TABLE film_actor');
        $this->addSql('DROP TABLE series_actor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE cartoon_actor (cartoon_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_7841CEB9C429E136 (cartoon_id), INDEX IDX_7841CEB910DAF24A (actor_id), PRIMARY KEY(cartoon_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE classicalfilm_actor (classical_film_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_BA346D6DA2AC6F6E (classical_film_id), INDEX IDX_BA346D6D10DAF24A (actor_id), PRIMARY KEY(classical_film_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE film_actor (film_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_DD19A8A9567F5183 (film_id), INDEX IDX_DD19A8A910DAF24A (actor_id), PRIMARY KEY(film_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE series_actor (series_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_31FAB2E410DAF24A (actor_id), INDEX IDX_31FAB2E45278319C (series_id), PRIMARY KEY(series_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cartoon_actor ADD CONSTRAINT FK_7841CEB9C429E136 FOREIGN KEY (cartoon_id) REFERENCES cartoon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cartoon_actor ADD CONSTRAINT FK_7841CEB910DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_actor ADD CONSTRAINT FK_BA346D6DA2AC6F6E FOREIGN KEY (classical_film_id) REFERENCES classical_film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_actor ADD CONSTRAINT FK_BA346D6D10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_actor ADD CONSTRAINT FK_DD19A8A9567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_actor ADD CONSTRAINT FK_DD19A8A910DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_actor ADD CONSTRAINT FK_31FAB2E410DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_actor ADD CONSTRAINT FK_31FAB2E45278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
    }
}
