<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831095310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hebdoo (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E52E837C82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebdoo_tags (hebdoo_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_E08D9C65F7D292A6 (hebdoo_id), INDEX IDX_E08D9C658D7B4FB4 (tags_id), PRIMARY KEY(hebdoo_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hebdoo ADD CONSTRAINT FK_E52E837C82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id)');
        $this->addSql('ALTER TABLE hebdoo_tags ADD CONSTRAINT FK_E08D9C65F7D292A6 FOREIGN KEY (hebdoo_id) REFERENCES hebdoo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hebdoo_tags ADD CONSTRAINT FK_E08D9C658D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebdoo DROP FOREIGN KEY FK_E52E837C82F1BAF4');
        $this->addSql('ALTER TABLE hebdoo_tags DROP FOREIGN KEY FK_E08D9C65F7D292A6');
        $this->addSql('ALTER TABLE hebdoo_tags DROP FOREIGN KEY FK_E08D9C658D7B4FB4');
        $this->addSql('DROP TABLE hebdoo');
        $this->addSql('DROP TABLE hebdoo_tags');
    }
}
