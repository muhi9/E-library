<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240526113821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_books (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, INDEX IDX_A8D9D1CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_books_book (user_books_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_4F0BC5F29D554820 (user_books_id), INDEX IDX_4F0BC5F216A2B381 (book_id), PRIMARY KEY(user_books_id, book_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_books ADD CONSTRAINT FK_A8D9D1CAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_books_book ADD CONSTRAINT FK_4F0BC5F29D554820 FOREIGN KEY (user_books_id) REFERENCES user_books (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_books_book ADD CONSTRAINT FK_4F0BC5F216A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_books DROP FOREIGN KEY FK_A8D9D1CAA76ED395');
        $this->addSql('ALTER TABLE user_books_book DROP FOREIGN KEY FK_4F0BC5F29D554820');
        $this->addSql('ALTER TABLE user_books_book DROP FOREIGN KEY FK_4F0BC5F216A2B381');
        $this->addSql('DROP TABLE user_books');
        $this->addSql('DROP TABLE user_books_book');
    }
}
