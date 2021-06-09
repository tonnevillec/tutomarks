<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210609084150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attachments (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tutos ADD attachment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE464E68B FOREIGN KEY (attachment_id) REFERENCES attachments (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EE0076DE464E68B ON tutos (attachment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE464E68B');
        $this->addSql('DROP TABLE attachments');
        $this->addSql('DROP INDEX UNIQ_EE0076DE464E68B ON tutos');
        $this->addSql('ALTER TABLE tutos DROP attachment_id');
    }
}
