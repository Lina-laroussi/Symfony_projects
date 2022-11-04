<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221104191348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_CFBDFA14DDEAB1A3 ON note');
        $this->addSql('ALTER TABLE note CHANGE etudiant_id etudiant INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14717E22E3 FOREIGN KEY (etudiant) REFERENCES etudiant (cin)');
        $this->addSql('CREATE INDEX IDX_CFBDFA14717E22E3 ON note (etudiant)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14717E22E3');
        $this->addSql('DROP INDEX IDX_CFBDFA14717E22E3 ON note');
        $this->addSql('ALTER TABLE note CHANGE etudiant etudiant_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_CFBDFA14DDEAB1A3 ON note (etudiant_id)');
    }
}
