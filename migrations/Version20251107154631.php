<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107154631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driver (id INT AUTO_INCREMENT NOT NULL, team_id INT DEFAULT NULL, teams_id INT NOT NULL, first_name VARCHAR(40) NOT NULL, last_name VARCHAR(40) NOT NULL, license_points INT NOT NULL, started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(20) NOT NULL, INDEX IDX_11667CD9296CD8AE (team_id), INDEX IDX_11667CD9D6365F12 (teams_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, brand VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_E8A81A8D296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE infraction (id INT AUTO_INCREMENT NOT NULL, driver_id INT DEFAULT NULL, team_id INT DEFAULT NULL, type VARCHAR(20) NOT NULL, points INT DEFAULT NULL, amount NUMERIC(8, 2) DEFAULT NULL, race_name VARCHAR(120) NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, INDEX IDX_C1A458F5C3423909 (driver_id), INDEX IDX_C1A458F5296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(50) NOT NULL, unit_price NUMERIC(5, 2) NOT NULL, created_at DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, storage SMALLINT NOT NULL, INDEX IDX_D34A04AD12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, engine_id INT NOT NULL, name VARCHAR(80) NOT NULL, UNIQUE INDEX UNIQ_C4E0A61FE78C9C0A (engine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE driver ADD CONSTRAINT FK_11667CD9D6365F12 FOREIGN KEY (teams_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE engine ADD CONSTRAINT FK_E8A81A8D296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE infraction ADD CONSTRAINT FK_C1A458F5C3423909 FOREIGN KEY (driver_id) REFERENCES driver (id)');
        $this->addSql('ALTER TABLE infraction ADD CONSTRAINT FK_C1A458F5296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FE78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9296CD8AE');
        $this->addSql('ALTER TABLE driver DROP FOREIGN KEY FK_11667CD9D6365F12');
        $this->addSql('ALTER TABLE engine DROP FOREIGN KEY FK_E8A81A8D296CD8AE');
        $this->addSql('ALTER TABLE infraction DROP FOREIGN KEY FK_C1A458F5C3423909');
        $this->addSql('ALTER TABLE infraction DROP FOREIGN KEY FK_C1A458F5296CD8AE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FE78C9C0A');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE driver');
        $this->addSql('DROP TABLE engine');
        $this->addSql('DROP TABLE infraction');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
    }
}
