<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615125904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tutos_id INT NOT NULL, commented_at DATETIME NOT NULL, is_validate TINYINT(1) NOT NULL, INDEX IDX_5F9E962AA76ED395 (user_id), INDEX IDX_5F9E962A98330394 (tutos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_tutos (tags_id INT NOT NULL, tutos_id INT NOT NULL, INDEX IDX_856480868D7B4FB4 (tags_id), INDEX IDX_8564808698330394 (tutos_id), PRIMARY KEY(tags_id, tutos_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluations (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tutos_id INT NOT NULL, notation INT NOT NULL, INDEX IDX_3B72691DA76ED395 (user_id), INDEX IDX_3B72691D98330394 (tutos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tutos (id INT AUTO_INCREMENT NOT NULL, published_by_id INT NOT NULL, published_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, creator VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_EE0076DE5B075477 (published_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A98330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id)');
        $this->addSql('ALTER TABLE tags_tutos ADD CONSTRAINT FK_856480868D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_tutos ADD CONSTRAINT FK_8564808698330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691D98330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id)');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE5B075477 FOREIGN KEY (published_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tags_tutos DROP FOREIGN KEY FK_856480868D7B4FB4');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A98330394');
        $this->addSql('ALTER TABLE tags_tutos DROP FOREIGN KEY FK_8564808698330394');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691D98330394');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_tutos');
        $this->addSql('DROP TABLE evaluations');
        $this->addSql('DROP TABLE tutos');
    }
}
