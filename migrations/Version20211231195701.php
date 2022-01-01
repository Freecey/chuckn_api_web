<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211231195701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE report (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, ip VARCHAR(255) NOT NULL, reason VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE report_jokes (report_id INT NOT NULL, jokes_id INT NOT NULL, INDEX IDX_C9FDE3784BD2A4C0 (report_id), INDEX IDX_C9FDE3788A9C4BE2 (jokes_id), PRIMARY KEY(report_id, jokes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE report_jokes ADD CONSTRAINT FK_C9FDE3784BD2A4C0 FOREIGN KEY (report_id) REFERENCES report (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE report_jokes ADD CONSTRAINT FK_C9FDE3788A9C4BE2 FOREIGN KEY (jokes_id) REFERENCES jokes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE report_jokes DROP FOREIGN KEY FK_C9FDE3784BD2A4C0');
        $this->addSql('DROP TABLE report');
        $this->addSql('DROP TABLE report_jokes');
    }
}
