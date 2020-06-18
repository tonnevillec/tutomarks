<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200617111957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE langues (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, shortname VARCHAR(4) NOT NULL, logo VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tutos ADD langue_id INT');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE2AADBACD FOREIGN KEY (langue_id) REFERENCES langues (id)');
        $this->addSql('CREATE INDEX IDX_EE0076DE2AADBACD ON tutos (langue_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE2AADBACD');
        $this->addSql('DROP TABLE langues');
        $this->addSql('DROP INDEX IDX_EE0076DE2AADBACD ON tutos');
        $this->addSql('ALTER TABLE tutos DROP langue_id');
    }
}
