<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220831133051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hebdoo_semaine (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, is_publish TINYINT(1) NOT NULL, youtube VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hebdoo_semaine_hebdoo (hebdoo_semaine_id INT NOT NULL, hebdoo_id INT NOT NULL, INDEX IDX_2A8B159B96C1CEA (hebdoo_semaine_id), INDEX IDX_2A8B159BF7D292A6 (hebdoo_id), PRIMARY KEY(hebdoo_semaine_id, hebdoo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo ADD CONSTRAINT FK_2A8B159B96C1CEA FOREIGN KEY (hebdoo_semaine_id) REFERENCES hebdoo_semaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo ADD CONSTRAINT FK_2A8B159BF7D292A6 FOREIGN KEY (hebdoo_id) REFERENCES hebdoo (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo DROP FOREIGN KEY FK_2A8B159B96C1CEA');
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo DROP FOREIGN KEY FK_2A8B159BF7D292A6');
        $this->addSql('DROP TABLE hebdoo_semaine');
        $this->addSql('DROP TABLE hebdoo_semaine_hebdoo');
    }
}
