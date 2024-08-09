<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240711112242 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D37E3C61F9');
        $this->addSql('DROP INDEX IDX_6000B0D37E3C61F9 ON notifications');
        $this->addSql('ALTER TABLE notifications CHANGE owner_id creator_id INT DEFAULT NULL, CHANGE title subject VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D361220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6000B0D361220EA6 ON notifications (creator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D361220EA6');
        $this->addSql('DROP INDEX IDX_6000B0D361220EA6 ON notifications');
        $this->addSql('ALTER TABLE notifications CHANGE creator_id owner_id INT DEFAULT NULL, CHANGE subject title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D37E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_6000B0D37E3C61F9 ON notifications (owner_id)');
    }
}
