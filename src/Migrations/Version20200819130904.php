<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819130904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE dms_folder (id INT AUTO_INCREMENT NOT NULL, parent_dms_folder_id INT DEFAULT NULL, name VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_3C880DCC74BFBCE5 (parent_dms_folder_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dms_folder ADD CONSTRAINT FK_3C880DCC74BFBCE5 FOREIGN KEY (parent_dms_folder_id) REFERENCES dms_folder (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3C880DCC5E237E0674BFBCE5 ON dms_folder (name, parent_dms_folder_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_3C880DCC5E237E0674BFBCE5 ON dms_folder');
        $this->addSql('ALTER TABLE dms_folder DROP FOREIGN KEY FK_3C880DCC74BFBCE5');
        $this->addSql('DROP TABLE dms_folder');
    }
}
