<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240311095340 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD publishing_house VARCHAR(60) DEFAULT NULL');
        $this->addSql('DROP INDEX `primary` ON category_book');
        $this->addSql('ALTER TABLE category_book ADD PRIMARY KEY (book_id, category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP publishing_house');
        $this->addSql('DROP INDEX `PRIMARY` ON category_book');
        $this->addSql('ALTER TABLE category_book ADD PRIMARY KEY (category_id, book_id)');
    }
}
