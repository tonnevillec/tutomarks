<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914094351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links ADD type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE youtube_links DROP title, DROP published_at, DROP url, DROP is_publish, DROP description, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE youtube_links ADD CONSTRAINT FK_308B6FDBF396750 FOREIGN KEY (id) REFERENCES links (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links DROP type');
        $this->addSql('ALTER TABLE youtube_links DROP FOREIGN KEY FK_308B6FDBF396750');
        $this->addSql('ALTER TABLE youtube_links ADD title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD published_at DATETIME NOT NULL, ADD url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD is_publish TINYINT(1) NOT NULL, ADD description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
