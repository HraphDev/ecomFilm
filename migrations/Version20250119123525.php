<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119123525 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cartoon_category (cartoon_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_EEFA609AC429E136 (cartoon_id), INDEX IDX_EEFA609A12469DE2 (category_id), PRIMARY KEY(cartoon_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cartoon_director (cartoon_id INT NOT NULL, director_id INT NOT NULL, INDEX IDX_F626AAABC429E136 (cartoon_id), INDEX IDX_F626AAAB899FB366 (director_id), PRIMARY KEY(cartoon_id, director_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cartoon_actor (cartoon_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_7841CEB9C429E136 (cartoon_id), INDEX IDX_7841CEB910DAF24A (actor_id), PRIMARY KEY(cartoon_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classicalfilm_category (classical_film_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_C13F972DA2AC6F6E (classical_film_id), INDEX IDX_C13F972D12469DE2 (category_id), PRIMARY KEY(classical_film_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classicalfilm_director (classical_film_id INT NOT NULL, director_id INT NOT NULL, INDEX IDX_D9E35D1CA2AC6F6E (classical_film_id), INDEX IDX_D9E35D1C899FB366 (director_id), PRIMARY KEY(classical_film_id, director_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE classicalfilm_actor (classical_film_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_BA346D6DA2AC6F6E (classical_film_id), INDEX IDX_BA346D6D10DAF24A (actor_id), PRIMARY KEY(classical_film_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE director (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE film_category (film_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_A4CBD6A8567F5183 (film_id), INDEX IDX_A4CBD6A812469DE2 (category_id), PRIMARY KEY(film_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE film_director (film_id INT NOT NULL, director_id INT NOT NULL, INDEX IDX_BC171C99567F5183 (film_id), INDEX IDX_BC171C99899FB366 (director_id), PRIMARY KEY(film_id, director_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE film_actor (film_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_DD19A8A9567F5183 (film_id), INDEX IDX_DD19A8A910DAF24A (actor_id), PRIMARY KEY(film_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series_category (series_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_55A781CE5278319C (series_id), INDEX IDX_55A781CE12469DE2 (category_id), PRIMARY KEY(series_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series_director (series_id INT NOT NULL, director_id INT NOT NULL, INDEX IDX_4D7B4BFF5278319C (series_id), INDEX IDX_4D7B4BFF899FB366 (director_id), PRIMARY KEY(series_id, director_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE series_actor (series_id INT NOT NULL, actor_id INT NOT NULL, INDEX IDX_31FAB2E45278319C (series_id), INDEX IDX_31FAB2E410DAF24A (actor_id), PRIMARY KEY(series_id, actor_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cartoon_category ADD CONSTRAINT FK_EEFA609AC429E136 FOREIGN KEY (cartoon_id) REFERENCES cartoon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cartoon_category ADD CONSTRAINT FK_EEFA609A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cartoon_director ADD CONSTRAINT FK_F626AAABC429E136 FOREIGN KEY (cartoon_id) REFERENCES cartoon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cartoon_director ADD CONSTRAINT FK_F626AAAB899FB366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cartoon_actor ADD CONSTRAINT FK_7841CEB9C429E136 FOREIGN KEY (cartoon_id) REFERENCES cartoon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cartoon_actor ADD CONSTRAINT FK_7841CEB910DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_category ADD CONSTRAINT FK_C13F972DA2AC6F6E FOREIGN KEY (classical_film_id) REFERENCES classical_film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_category ADD CONSTRAINT FK_C13F972D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_director ADD CONSTRAINT FK_D9E35D1CA2AC6F6E FOREIGN KEY (classical_film_id) REFERENCES classical_film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_director ADD CONSTRAINT FK_D9E35D1C899FB366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_actor ADD CONSTRAINT FK_BA346D6DA2AC6F6E FOREIGN KEY (classical_film_id) REFERENCES classical_film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE classicalfilm_actor ADD CONSTRAINT FK_BA346D6D10DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_category ADD CONSTRAINT FK_A4CBD6A8567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_category ADD CONSTRAINT FK_A4CBD6A812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_director ADD CONSTRAINT FK_BC171C99567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_director ADD CONSTRAINT FK_BC171C99899FB366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_actor ADD CONSTRAINT FK_DD19A8A9567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE film_actor ADD CONSTRAINT FK_DD19A8A910DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_category ADD CONSTRAINT FK_55A781CE5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_category ADD CONSTRAINT FK_55A781CE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_director ADD CONSTRAINT FK_4D7B4BFF5278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_director ADD CONSTRAINT FK_4D7B4BFF899FB366 FOREIGN KEY (director_id) REFERENCES director (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_actor ADD CONSTRAINT FK_31FAB2E45278319C FOREIGN KEY (series_id) REFERENCES series (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE series_actor ADD CONSTRAINT FK_31FAB2E410DAF24A FOREIGN KEY (actor_id) REFERENCES actor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cartoon DROP category');
        $this->addSql('ALTER TABLE classical_film DROP category');
        $this->addSql('ALTER TABLE film DROP created_at, DROP category');
        $this->addSql('ALTER TABLE series DROP category');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cartoon_category DROP FOREIGN KEY FK_EEFA609AC429E136');
        $this->addSql('ALTER TABLE cartoon_category DROP FOREIGN KEY FK_EEFA609A12469DE2');
        $this->addSql('ALTER TABLE cartoon_director DROP FOREIGN KEY FK_F626AAABC429E136');
        $this->addSql('ALTER TABLE cartoon_director DROP FOREIGN KEY FK_F626AAAB899FB366');
        $this->addSql('ALTER TABLE cartoon_actor DROP FOREIGN KEY FK_7841CEB9C429E136');
        $this->addSql('ALTER TABLE cartoon_actor DROP FOREIGN KEY FK_7841CEB910DAF24A');
        $this->addSql('ALTER TABLE classicalfilm_category DROP FOREIGN KEY FK_C13F972DA2AC6F6E');
        $this->addSql('ALTER TABLE classicalfilm_category DROP FOREIGN KEY FK_C13F972D12469DE2');
        $this->addSql('ALTER TABLE classicalfilm_director DROP FOREIGN KEY FK_D9E35D1CA2AC6F6E');
        $this->addSql('ALTER TABLE classicalfilm_director DROP FOREIGN KEY FK_D9E35D1C899FB366');
        $this->addSql('ALTER TABLE classicalfilm_actor DROP FOREIGN KEY FK_BA346D6DA2AC6F6E');
        $this->addSql('ALTER TABLE classicalfilm_actor DROP FOREIGN KEY FK_BA346D6D10DAF24A');
        $this->addSql('ALTER TABLE film_category DROP FOREIGN KEY FK_A4CBD6A8567F5183');
        $this->addSql('ALTER TABLE film_category DROP FOREIGN KEY FK_A4CBD6A812469DE2');
        $this->addSql('ALTER TABLE film_director DROP FOREIGN KEY FK_BC171C99567F5183');
        $this->addSql('ALTER TABLE film_director DROP FOREIGN KEY FK_BC171C99899FB366');
        $this->addSql('ALTER TABLE film_actor DROP FOREIGN KEY FK_DD19A8A9567F5183');
        $this->addSql('ALTER TABLE film_actor DROP FOREIGN KEY FK_DD19A8A910DAF24A');
        $this->addSql('ALTER TABLE series_category DROP FOREIGN KEY FK_55A781CE5278319C');
        $this->addSql('ALTER TABLE series_category DROP FOREIGN KEY FK_55A781CE12469DE2');
        $this->addSql('ALTER TABLE series_director DROP FOREIGN KEY FK_4D7B4BFF5278319C');
        $this->addSql('ALTER TABLE series_director DROP FOREIGN KEY FK_4D7B4BFF899FB366');
        $this->addSql('ALTER TABLE series_actor DROP FOREIGN KEY FK_31FAB2E45278319C');
        $this->addSql('ALTER TABLE series_actor DROP FOREIGN KEY FK_31FAB2E410DAF24A');
        $this->addSql('DROP TABLE actor');
        $this->addSql('DROP TABLE cartoon_category');
        $this->addSql('DROP TABLE cartoon_director');
        $this->addSql('DROP TABLE cartoon_actor');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE classicalfilm_category');
        $this->addSql('DROP TABLE classicalfilm_director');
        $this->addSql('DROP TABLE classicalfilm_actor');
        $this->addSql('DROP TABLE director');
        $this->addSql('DROP TABLE film_category');
        $this->addSql('DROP TABLE film_director');
        $this->addSql('DROP TABLE film_actor');
        $this->addSql('DROP TABLE series_category');
        $this->addSql('DROP TABLE series_director');
        $this->addSql('DROP TABLE series_actor');
        $this->addSql('ALTER TABLE cartoon ADD category VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE classical_film ADD category VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE film ADD created_at DATETIME NOT NULL, ADD category VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE series ADD category VARCHAR(50) NOT NULL');
    }
}
