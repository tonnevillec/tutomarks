<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211215091937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE464E68B');
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE12469DE2');
        $this->addSql('ALTER TABLE tutos_tags DROP FOREIGN KEY FK_8AF504278D7B4FB4');
        $this->addSql('ALTER TABLE badges_unlock DROP FOREIGN KEY FK_3B8A29C9F7A2C2FC');
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE72F5A1AA');
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE2AADBACD');
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE5FB14BA7');
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DED614C7E7');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A98330394');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691D98330394');
        $this->addSql('ALTER TABLE tutos_tags DROP FOREIGN KEY FK_8AF5042798330394');
        $this->addSql('ALTER TABLE user_tutos_informations DROP FOREIGN KEY FK_28D3E7DB98330394');
        $this->addSql('ALTER TABLE badges_unlock DROP FOREIGN KEY FK_3B8A29C9A76ED395');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AA76ED395');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691DA76ED395');
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE5B075477');
        $this->addSql('ALTER TABLE user_tutos_informations DROP FOREIGN KEY FK_28D3E7DBA76ED395');
        $this->addSql('CREATE TABLE attachments (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) DEFAULT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE _attachments');
        $this->addSql('DROP TABLE _categories');
        $this->addSql('DROP TABLE _tags');
        $this->addSql('DROP TABLE badges');
        $this->addSql('DROP TABLE badges_unlock');
        $this->addSql('DROP TABLE channels');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE evaluations');
        $this->addSql('DROP TABLE langues');
        $this->addSql('DROP TABLE levels');
        $this->addSql('DROP TABLE migration_versions');
        $this->addSql('DROP TABLE prices');
        $this->addSql('DROP TABLE tutos');
        $this->addSql('DROP TABLE tutos_tags');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_tutos_informations');
        $this->addSql('ALTER TABLE authors ADD attachment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE authors ADD CONSTRAINT FK_8E0C2A51464E68B FOREIGN KEY (attachment_id) REFERENCES attachments (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E0C2A51464E68B ON authors (attachment_id)');
        $this->addSql('ALTER TABLE tags DROP logo, DROP updated_at');
        $this->addSql('ALTER TABLE users CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authors DROP FOREIGN KEY FK_8E0C2A51464E68B');
        $this->addSql('CREATE TABLE _attachments (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE _categories (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, homekey VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, with_videos TINYINT(1) NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE _tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE badges (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, action_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, action_count INT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE badges_unlock (id INT AUTO_INCREMENT NOT NULL, badge_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_3B8A29C9A76ED395 (user_id), INDEX IDX_3B8A29C9F7A2C2FC (badge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE channels (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, thumbnails_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, site_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, youtube_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, youtube_custom_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, twitter VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, github VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tutos_id INT NOT NULL, commented_at DATETIME NOT NULL, is_validate TINYINT(1) NOT NULL, comment LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_5F9E962A98330394 (tutos_id), INDEX IDX_5F9E962AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evaluations (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tutos_id INT NOT NULL, notation DOUBLE PRECISION NOT NULL, INDEX IDX_3B72691D98330394 (tutos_id), INDEX IDX_3B72691DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE langues (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, shortname VARCHAR(4) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE levels (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, `rank` INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE migration_versions (version VARCHAR(14) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, executed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(version)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prices (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tutos (id INT AUTO_INCREMENT NOT NULL, published_by_id INT NOT NULL, category_id INT DEFAULT NULL, langue_id INT NOT NULL, price_id INT DEFAULT NULL, level_id INT DEFAULT NULL, channel_id INT DEFAULT NULL, attachment_id INT DEFAULT NULL, published_at DATETIME NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, creator VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, moy DOUBLE PRECISION DEFAULT NULL, duration INT DEFAULT NULL, youtube_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, available TINYINT(1) NOT NULL, thumbnails_small VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, thumbnails_large VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_at DATETIME DEFAULT NULL, INDEX IDX_EE0076DE12469DE2 (category_id), INDEX IDX_EE0076DE2AADBACD (langue_id), FULLTEXT INDEX IDX_EE0076DE2B36786B6DE44026 (title, description), INDEX IDX_EE0076DE464E68B (attachment_id), INDEX IDX_EE0076DE5B075477 (published_by_id), INDEX IDX_EE0076DE5FB14BA7 (level_id), INDEX IDX_EE0076DE72F5A1AA (channel_id), INDEX IDX_EE0076DED614C7E7 (price_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tutos_tags (tutos_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_8AF504278D7B4FB4 (tags_id), INDEX IDX_8AF5042798330394 (tutos_id), PRIMARY KEY(tutos_id, tags_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, is_actif TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, last_connection DATETIME DEFAULT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, github_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, google_id VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_tutos_informations (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tutos_id INT NOT NULL, shown TINYINT(1) NOT NULL, pined TINYINT(1) NOT NULL, postit LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_28D3E7DB98330394 (tutos_id), INDEX IDX_28D3E7DBA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE badges_unlock ADD CONSTRAINT FK_3B8A29C9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE badges_unlock ADD CONSTRAINT FK_3B8A29C9F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badges (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A98330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691D98330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE12469DE2 FOREIGN KEY (category_id) REFERENCES _categories (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE2AADBACD FOREIGN KEY (langue_id) REFERENCES langues (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE464E68B FOREIGN KEY (attachment_id) REFERENCES _attachments (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE5B075477 FOREIGN KEY (published_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE5FB14BA7 FOREIGN KEY (level_id) REFERENCES levels (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE72F5A1AA FOREIGN KEY (channel_id) REFERENCES channels (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DED614C7E7 FOREIGN KEY (price_id) REFERENCES prices (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE tutos_tags ADD CONSTRAINT FK_8AF504278D7B4FB4 FOREIGN KEY (tags_id) REFERENCES _tags (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutos_tags ADD CONSTRAINT FK_8AF5042798330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_tutos_informations ADD CONSTRAINT FK_28D3E7DB98330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE user_tutos_informations ADD CONSTRAINT FK_28D3E7DBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE attachments');
        $this->addSql('DROP INDEX UNIQ_8E0C2A51464E68B ON authors');
        $this->addSql('ALTER TABLE authors DROP attachment_id');
        $this->addSql('ALTER TABLE tags ADD logo VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD updated_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE users CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
