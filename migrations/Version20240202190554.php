<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240202190554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart ADD cart_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B75CB41B92 FOREIGN KEY (cart_user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_BA388B75CB41B92 ON cart (cart_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B75CB41B92');
        $this->addSql('DROP INDEX IDX_BA388B75CB41B92 ON cart');
        $this->addSql('ALTER TABLE cart DROP cart_user_id');
    }
}
