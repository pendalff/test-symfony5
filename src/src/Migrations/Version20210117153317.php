<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210117153317 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Inital ';
    }

    public function up(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE categories (id INT UNSIGNED AUTO_INCREMENT NOT NULL, parent_id INT UNSIGNED DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX category_parent (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, category_id INT UNSIGNED DEFAULT NULL, state TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, slug VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, preview VARCHAR(500) NOT NULL, content TEXT(65535) NOT NULL, image VARCHAR(255) DEFAULT NULL, UNIQUE INDEX unique_news_slug (slug), INDEX news_categoryId (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, news_id INT DEFAULT NULL, text VARCHAR(500) NOT NULL, INDEX news (news_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE categories ADD CONSTRAINT foreign_categories_parent FOREIGN KEY (parent_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT foreign_news_category FOREIGN KEY (category_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT foreign_comments_news FOREIGN KEY (news_id) REFERENCES news (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('TRUNCATE TABLE comments');
        $this->addSql('TRUNCATE TABLE news');
        $this->addSql('TRUNCATE TABLE categories');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY foreign_categories_parent');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY foreign_news_category');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY foreign_comments_news');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE news');
    }
}
