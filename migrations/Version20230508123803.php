<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508123803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hebdoo DROP FOREIGN KEY FK_E52E837C82F1BAF4');
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo DROP FOREIGN KEY FK_2A8B159B96C1CEA');
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo DROP FOREIGN KEY FK_2A8B159BF7D292A6');
        $this->addSql('ALTER TABLE hebdoo_tags DROP FOREIGN KEY FK_E08D9C65F7D292A6');
        $this->addSql('ALTER TABLE hebdoo_tags DROP FOREIGN KEY FK_E08D9C658D7B4FB4');
        $this->addSql('DROP TABLE hebdoo');
        $this->addSql('DROP TABLE hebdoo_semaine');
        $this->addSql('DROP TABLE hebdoo_semaine_hebdoo');
        $this->addSql('DROP TABLE hebdoo_tags');
        $this->addSql('ALTER TABLE languages DROP name, DROP shortname, DROP logo');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hebdoo (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, pseudo VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX IDX_E52E837C82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE hebdoo_semaine (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, is_publish TINYINT(1) NOT NULL, youtube VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE hebdoo_semaine_hebdoo (hebdoo_semaine_id INT NOT NULL, hebdoo_id INT NOT NULL, INDEX IDX_2A8B159B96C1CEA (hebdoo_semaine_id), INDEX IDX_2A8B159BF7D292A6 (hebdoo_id), PRIMARY KEY(hebdoo_semaine_id, hebdoo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE hebdoo_tags (hebdoo_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_E08D9C65F7D292A6 (hebdoo_id), INDEX IDX_E08D9C658D7B4FB4 (tags_id), PRIMARY KEY(hebdoo_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE hebdoo ADD CONSTRAINT FK_E52E837C82F1BAF4 FOREIGN KEY (language_id) REFERENCES languages (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo ADD CONSTRAINT FK_2A8B159B96C1CEA FOREIGN KEY (hebdoo_semaine_id) REFERENCES hebdoo_semaine (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hebdoo_semaine_hebdoo ADD CONSTRAINT FK_2A8B159BF7D292A6 FOREIGN KEY (hebdoo_id) REFERENCES hebdoo (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hebdoo_tags ADD CONSTRAINT FK_E08D9C65F7D292A6 FOREIGN KEY (hebdoo_id) REFERENCES hebdoo (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hebdoo_tags ADD CONSTRAINT FK_E08D9C658D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE languages ADD name VARCHAR(255) NOT NULL, ADD shortname VARCHAR(3) DEFAULT NULL, ADD logo VARCHAR(255) DEFAULT NULL');
    }
}
