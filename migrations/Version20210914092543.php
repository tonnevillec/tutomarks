<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210914092543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE autors (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, site_url VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, github VARCHAR(255) DEFAULT NULL, twitch VARCHAR(255) DEFAULT NULL, youtube VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE languages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, shortname VARCHAR(3) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE links (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, autor_id INT NOT NULL, language_id INT NOT NULL, title VARCHAR(255) NOT NULL, published_at DATETIME NOT NULL, url VARCHAR(255) NOT NULL, is_publish TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_D182A11812469DE2 (category_id), INDEX IDX_D182A11814D45BBE (autor_id), INDEX IDX_D182A11882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE links_tags (links_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_ED4C7DAEC0DE588D (links_id), INDEX IDX_ED4C7DAE8D7B4FB4 (tags_id), PRIMARY KEY(links_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE youtube_links (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, published_at DATETIME NOT NULL, url VARCHAR(255) NOT NULL, is_publish TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, youtube_id VARCHAR(255) NOT NULL, img_small VARCHAR(255) NOT NULL, img_medium VARCHAR(255) NOT NULL, img_large VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A11812469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A11814D45BBE FOREIGN KEY (autor_id) REFERENCES autors (id)');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A11882F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id)');
        $this->addSql('ALTER TABLE links_tags ADD CONSTRAINT FK_ED4C7DAEC0DE588D FOREIGN KEY (links_id) REFERENCES links (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE links_tags ADD CONSTRAINT FK_ED4C7DAE8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A11814D45BBE');
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A11812469DE2');
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A11882F1BAF4');
        $this->addSql('ALTER TABLE links_tags DROP FOREIGN KEY FK_ED4C7DAEC0DE588D');
        $this->addSql('ALTER TABLE links_tags DROP FOREIGN KEY FK_ED4C7DAE8D7B4FB4');
        $this->addSql('DROP TABLE autors');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE languages');
        $this->addSql('DROP TABLE links');
        $this->addSql('DROP TABLE links_tags');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE youtube_links');
    }
}
