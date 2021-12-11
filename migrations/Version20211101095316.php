<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211101095316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A11814D45BBE');
        $this->addSql('CREATE TABLE authors (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, site_url VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, github VARCHAR(255) DEFAULT NULL, twitch VARCHAR(255) DEFAULT NULL, youtube VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, yt_logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE autors');
        $this->addSql('DROP INDEX IDX_D182A11814D45BBE ON links');
        $this->addSql('ALTER TABLE links CHANGE autor_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A118F675F31B FOREIGN KEY (author_id) REFERENCES authors (id)');
        $this->addSql('CREATE INDEX IDX_D182A118F675F31B ON links (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A118F675F31B');
        $this->addSql('CREATE TABLE autors (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, site_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, twitter VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, github VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, twitch VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, youtube VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME DEFAULT NULL, yt_logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE authors');
        $this->addSql('DROP INDEX IDX_D182A118F675F31B ON links');
        $this->addSql('ALTER TABLE links CHANGE author_id autor_id INT NOT NULL');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A11814D45BBE FOREIGN KEY (autor_id) REFERENCES autors (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D182A11814D45BBE ON links (autor_id)');
    }
}
