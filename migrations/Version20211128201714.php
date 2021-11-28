<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211128201714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jokes_ratings (id INT AUTO_INCREMENT NOT NULL, rating INT NOT NULL, created_at DATETIME NOT NULL, ip VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jokes_ratings_jokes (jokes_ratings_id INT NOT NULL, jokes_id INT NOT NULL, INDEX IDX_7FAE8F5B29A2DDD6 (jokes_ratings_id), INDEX IDX_7FAE8F5B8A9C4BE2 (jokes_id), PRIMARY KEY(jokes_ratings_id, jokes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jokes_ratings_jokes ADD CONSTRAINT FK_7FAE8F5B29A2DDD6 FOREIGN KEY (jokes_ratings_id) REFERENCES jokes_ratings (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE jokes_ratings_jokes ADD CONSTRAINT FK_7FAE8F5B8A9C4BE2 FOREIGN KEY (jokes_id) REFERENCES jokes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jokes_ratings_jokes DROP FOREIGN KEY FK_7FAE8F5B29A2DDD6');
        $this->addSql('DROP TABLE jokes_ratings');
        $this->addSql('DROP TABLE jokes_ratings_jokes');
    }
}
