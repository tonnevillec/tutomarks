<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210120100803 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_tutos_informations (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, tutos_id INT NOT NULL, shown TINYINT(1) NOT NULL, pined TINYINT(1) NOT NULL, postit LONGTEXT DEFAULT NULL, INDEX IDX_28D3E7DBA76ED395 (user_id), INDEX IDX_28D3E7DB98330394 (tutos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_tutos_informations ADD CONSTRAINT FK_28D3E7DBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_tutos_informations ADD CONSTRAINT FK_28D3E7DB98330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_tutos_informations');
    }
}
