<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230904074731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artiste (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, style VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artiste_festival (artiste_id INT NOT NULL, festival_id INT NOT NULL, INDEX IDX_48B9CB9021D25844 (artiste_id), INDEX IDX_48B9CB908AEBAF57 (festival_id), PRIMARY KEY(artiste_id, festival_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fragments (id INT AUTO_INCREMENT NOT NULL, lieu_id INT NOT NULL, nom VARCHAR(100) NOT NULL, affiche VARCHAR(255) DEFAULT NULL, INDEX IDX_57CF7896AB213CC (lieu_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artiste_festival ADD CONSTRAINT FK_48B9CB9021D25844 FOREIGN KEY (artiste_id) REFERENCES artiste (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artiste_festival ADD CONSTRAINT FK_48B9CB908AEBAF57 FOREIGN KEY (festival_id) REFERENCES fragments (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE fragments ADD CONSTRAINT FK_57CF7896AB213CC FOREIGN KEY (lieu_id) REFERENCES departement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artiste_festival DROP FOREIGN KEY FK_48B9CB9021D25844');
        $this->addSql('ALTER TABLE artiste_festival DROP FOREIGN KEY FK_48B9CB908AEBAF57');
        $this->addSql('ALTER TABLE fragments DROP FOREIGN KEY FK_57CF7896AB213CC');
        $this->addSql('DROP TABLE artiste');
        $this->addSql('DROP TABLE artiste_festival');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE fragments');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
