<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200928132600 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE badges (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, action_name VARCHAR(255) NOT NULL, action_count INT NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badges_unlock (id INT AUTO_INCREMENT NOT NULL, badge_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_3B8A29C9F7A2C2FC (badge_id), INDEX IDX_3B8A29C9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE badges_unlock ADD CONSTRAINT FK_3B8A29C9F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badges (id)');
        $this->addSql('ALTER TABLE badges_unlock ADD CONSTRAINT FK_3B8A29C9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badges_unlock DROP FOREIGN KEY FK_3B8A29C9F7A2C2FC');
        $this->addSql('DROP TABLE badges');
        $this->addSql('DROP TABLE badges_unlock');
    }
}
