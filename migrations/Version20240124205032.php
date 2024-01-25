<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124205032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE besoin (id INT AUTO_INCREMENT NOT NULL, plante_id INT DEFAULT NULL, quantite_eau INT NOT NULL, frec_arrosage INT NOT NULL, INDEX IDX_8118E811177B16E8 (plante_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plante (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, espece_plante VARCHAR(255) NOT NULL, date_achat DATETIME DEFAULT NULL, image_plante VARCHAR(255) DEFAULT NULL, is_arrosed TINYINT(1) DEFAULT NULL, INDEX IDX_517A6947A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE besoin ADD CONSTRAINT FK_8118E811177B16E8 FOREIGN KEY (plante_id) REFERENCES plante (id)');
        $this->addSql('ALTER TABLE plante ADD CONSTRAINT FK_517A6947A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE besoin DROP FOREIGN KEY FK_8118E811177B16E8');
        $this->addSql('ALTER TABLE plante DROP FOREIGN KEY FK_517A6947A76ED395');
        $this->addSql('DROP TABLE besoin');
        $this->addSql('DROP TABLE plante');
    }
}
