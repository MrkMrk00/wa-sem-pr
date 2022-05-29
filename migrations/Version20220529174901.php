<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220529174901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE body_style (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_90543CA25E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT UNSIGNED AUTO_INCREMENT NOT NULL, manufacturer_id INT UNSIGNED NOT NULL, body_style_id INT UNSIGNED NOT NULL, engine_id INT UNSIGNED NOT NULL, added_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, manufactured INT NOT NULL, generation VARCHAR(255) DEFAULT NULL, driven_axle VARCHAR(3) NOT NULL, seat_count INT NOT NULL, INDEX IDX_773DE69DA23B42D (manufacturer_id), INDEX IDX_773DE69DA348A0BC (body_style_id), INDEX IDX_773DE69DE78C9C0A (engine_id), INDEX IDX_773DE69D55B127A4 (added_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car_review (id INT UNSIGNED AUTO_INCREMENT NOT NULL, car_id INT UNSIGNED NOT NULL, user_id INT NOT NULL, value INT NOT NULL, INDEX IDX_612B6D34C3C6F69F (car_id), INDEX IDX_612B6D34A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE engine (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, size INT NOT NULL, number_of_cylinders INT NOT NULL, max_power INT NOT NULL, max_torque INT NOT NULL, aspiration VARCHAR(64) NOT NULL, fuel VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturer (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_3D0AE6DC5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DA348A0BC FOREIGN KEY (body_style_id) REFERENCES body_style (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69DE78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id)');
        $this->addSql('ALTER TABLE car ADD CONSTRAINT FK_773DE69D55B127A4 FOREIGN KEY (added_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE car_review ADD CONSTRAINT FK_612B6D34C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id)');
        $this->addSql('ALTER TABLE car_review ADD CONSTRAINT FK_612B6D34A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA348A0BC');
        $this->addSql('ALTER TABLE car_review DROP FOREIGN KEY FK_612B6D34C3C6F69F');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DE78C9C0A');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69DA23B42D');
        $this->addSql('ALTER TABLE car DROP FOREIGN KEY FK_773DE69D55B127A4');
        $this->addSql('ALTER TABLE car_review DROP FOREIGN KEY FK_612B6D34A76ED395');
        $this->addSql('DROP TABLE body_style');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE car_review');
        $this->addSql('DROP TABLE engine');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
