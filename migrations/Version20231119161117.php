<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119161117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_book_category DROP CONSTRAINT fk_7a5a379457511be2');
        $this->addSql('DROP INDEX idx_7a5a379457511be2');
        $this->addSql('ALTER TABLE book_book_category DROP CONSTRAINT book_book_category_pkey');
        $this->addSql('ALTER TABLE book_book_category RENAME COLUMN book_to_book_category TO book_id');
        $this->addSql('ALTER TABLE book_book_category ADD CONSTRAINT FK_7A5A379416A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7A5A379416A2B381 ON book_book_category (book_id)');
        $this->addSql('ALTER TABLE book_book_category ADD PRIMARY KEY (book_id, book_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book_book_category DROP CONSTRAINT FK_7A5A379416A2B381');
        $this->addSql('DROP INDEX IDX_7A5A379416A2B381');
        $this->addSql('DROP INDEX book_book_category_pkey');
        $this->addSql('ALTER TABLE book_book_category RENAME COLUMN book_id TO book_to_book_category');
        $this->addSql('ALTER TABLE book_book_category ADD CONSTRAINT fk_7a5a379457511be2 FOREIGN KEY (book_to_book_category) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7a5a379457511be2 ON book_book_category (book_to_book_category)');
        $this->addSql('ALTER TABLE book_book_category ADD PRIMARY KEY (book_to_book_category, book_category_id)');
    }
}
