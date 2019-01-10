<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181225192417 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property DROP image_name, CHANGE sold sold TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE filename filename VARCHAR(255) NOT NULL, CHANGE updated_at updated_at DATETIME');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE property ADD image_name VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE filename filename VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE sold sold TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE updated_at updated_at DATETIME DEFAULT NULL');
    }
}
