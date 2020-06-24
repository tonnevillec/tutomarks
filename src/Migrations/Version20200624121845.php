<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200624121845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutos ADD price_id INT DEFAULT NULL, ADD level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DED614C7E7 FOREIGN KEY (price_id) REFERENCES prices (id)');
        $this->addSql('ALTER TABLE tutos ADD CONSTRAINT FK_EE0076DE5FB14BA7 FOREIGN KEY (level_id) REFERENCES levels (id)');
        $this->addSql('CREATE INDEX IDX_EE0076DED614C7E7 ON tutos (price_id)');
        $this->addSql('CREATE INDEX IDX_EE0076DE5FB14BA7 ON tutos (level_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DED614C7E7');
        $this->addSql('ALTER TABLE tutos DROP FOREIGN KEY FK_EE0076DE5FB14BA7');
        $this->addSql('DROP INDEX IDX_EE0076DED614C7E7 ON tutos');
        $this->addSql('DROP INDEX IDX_EE0076DE5FB14BA7 ON tutos');
        $this->addSql('ALTER TABLE tutos DROP price_id, DROP level_id');
    }
}
