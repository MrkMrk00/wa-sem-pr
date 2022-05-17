<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220517140435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_90543CA25E237E06 ON body_style (name)');
        $this->addSql('ALTER TABLE car ADD manufacturer_id INT UNSIGNED NOT NULL, DROP manufacturer');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('CREATE INDEX IDX_773DE69DA23B42D ON car (manufacturer_id)');
        $this->addSql('ALTER TABLE manufacturer DROP cars');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX UNIQ_90543CA25E237E06 ON body_style');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA23B42D');
        $this->addSql('DROP INDEX IDX_773DE69DA23B42D ON car');
        $this->addSql('ALTER TABLE car ADD manufacturer VARCHAR(255) NOT NULL, DROP manufacturer_id');
        $this->addSql('ALTER TABLE manufacturer ADD cars VARCHAR(255) NOT NULL');
    }
}
