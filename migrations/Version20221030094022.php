<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221030094022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte ADD utilisateur_cin INT DEFAULT NULL, CHANGE ref ref INT NOT NULL');
        $this->addSql('ALTER TABLE compte ADD CONSTRAINT FK_CFF6526026A98CA9 FOREIGN KEY (utilisateur_cin) REFERENCES utilisateur (cin)');
        $this->addSql('CREATE INDEX IDX_CFF6526026A98CA9 ON compte (utilisateur_cin)');
        $this->addSql('ALTER TABLE utilisateur CHANGE cin cin INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte DROP FOREIGN KEY FK_CFF6526026A98CA9');
        $this->addSql('DROP INDEX IDX_CFF6526026A98CA9 ON compte');
        $this->addSql('ALTER TABLE compte DROP utilisateur_cin, CHANGE ref ref INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE cin cin INT AUTO_INCREMENT NOT NULL');
    }
}
