<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210924115013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links ADD published_by_id INT NOT NULL');
        $this->addSql('ALTER TABLE links ADD CONSTRAINT FK_D182A1185B075477 FOREIGN KEY (published_by_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_D182A1185B075477 ON links (published_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE links DROP FOREIGN KEY FK_D182A1185B075477');
        $this->addSql('DROP INDEX IDX_D182A1185B075477 ON links');
        $this->addSql('ALTER TABLE links DROP published_by_id');
    }
}
