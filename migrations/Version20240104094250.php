<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104094250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO book_format(id, title, description, comment) VALUES (1, 'eBook', 'Our eBooks come in DRM-free kindle', null)");
        $this->addSql("INSERT INTO book_format(id, title, description, comment) VALUES (2, 'print + eBook', 'Shiiesh', 'FFFF')");


    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM book_format');
    }
}
