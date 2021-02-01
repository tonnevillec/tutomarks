<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210127143617 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE channels (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, thumbnails_url VARCHAR(255) DEFAULT NULL, site_url VARCHAR(255) DEFAULT NULL, youtube_id VARCHAR(255) DEFAULT NULL, youtube_custom_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tutos ADD channel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE72F5A1AA FOREIGN KEY (channel_id) REFERENCES channels (id)');
        $this->addSql('CREATE INDEX IDX_EE0076DE72F5A1AA ON tutos (channel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE72F5A1AA');
        $this->addSql('DROP TABLE channels');
        $this->addSql('DROP INDEX IDX_EE0076DE72F5A1AA ON tutos');
        $this->addSql('ALTER TABLE tutos DROP channel_id');
    }
}
