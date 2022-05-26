<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220526131909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car_engine (car_id INT UNSIGNED NOT NULL, engine_id INT UNSIGNED NOT NULL, INDEX IDX_F0C0F67FC3C6F69F (car_id), INDEX IDX_F0C0F67FE78C9C0A (engine_id), PRIMARY KEY(car_id, engine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE car_engine ADD CONSTRAINT FK_F0C0F67FC3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE car_engine ADD CONSTRAINT FK_F0C0F67FE78C9C0A FOREIGN KEY (engine_id) REFERENCES engine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE engine ADD fuel VARCHAR(32) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE car_engine');
        $this->addSql('ALTER TABLE engine DROP fuel');
    }
}
