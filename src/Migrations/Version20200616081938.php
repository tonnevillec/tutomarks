<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616081938 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tutos_tags (tutos_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_8AF5042798330394 (tutos_id), INDEX IDX_8AF504278D7B4FB4 (tags_id), PRIMARY KEY(tutos_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tutos_tags ADD CONSTRAINT FK_8AF5042798330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tutos_tags ADD CONSTRAINT FK_8AF504278D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tags_tutos');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tags_tutos (tags_id INT NOT NULL, tutos_id INT NOT NULL, INDEX IDX_856480868D7B4FB4 (tags_id), INDEX IDX_8564808698330394 (tutos_id), PRIMARY KEY(tags_id, tutos_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE tags_tutos ADD CONSTRAINT FK_856480868D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_tutos ADD CONSTRAINT FK_8564808698330394 FOREIGN KEY (tutos_id) REFERENCES tutos (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tutos_tags');
    }
}
