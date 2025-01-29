<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250129111933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis ADD produits_id INT NOT NULL, ADD avis_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0CD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0197E709F FOREIGN KEY (avis_id) REFERENCES produits (id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0CD11A2CF ON avis (produits_id)');
        $this->addSql('CREATE INDEX IDX_8F91ABF0197E709F ON avis (avis_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0CD11A2CF');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0197E709F');
        $this->addSql('DROP INDEX IDX_8F91ABF0CD11A2CF ON avis');
        $this->addSql('DROP INDEX IDX_8F91ABF0197E709F ON avis');
        $this->addSql('ALTER TABLE avis DROP produits_id, DROP avis_id');
    }
}
