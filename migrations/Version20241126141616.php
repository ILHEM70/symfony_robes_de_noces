<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241126141616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE couleur (id INT AUTO_INCREMENT NOT NULL, couleur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_taille (produits_id INT NOT NULL, taille_id INT NOT NULL, INDEX IDX_E74190DBCD11A2CF (produits_id), INDEX IDX_E74190DBFF25611A (taille_id), PRIMARY KEY(produits_id, taille_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produits_couleur (produits_id INT NOT NULL, couleur_id INT NOT NULL, INDEX IDX_59F253CCD11A2CF (produits_id), INDEX IDX_59F253CC31BA576 (couleur_id), PRIMARY KEY(produits_id, couleur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taille (id INT AUTO_INCREMENT NOT NULL, taille INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produits_taille ADD CONSTRAINT FK_E74190DBCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_taille ADD CONSTRAINT FK_E74190DBFF25611A FOREIGN KEY (taille_id) REFERENCES taille (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_couleur ADD CONSTRAINT FK_59F253CCD11A2CF FOREIGN KEY (produits_id) REFERENCES produits (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produits_couleur ADD CONSTRAINT FK_59F253CC31BA576 FOREIGN KEY (couleur_id) REFERENCES couleur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits_taille DROP FOREIGN KEY FK_E74190DBCD11A2CF');
        $this->addSql('ALTER TABLE produits_taille DROP FOREIGN KEY FK_E74190DBFF25611A');
        $this->addSql('ALTER TABLE produits_couleur DROP FOREIGN KEY FK_59F253CCD11A2CF');
        $this->addSql('ALTER TABLE produits_couleur DROP FOREIGN KEY FK_59F253CC31BA576');
        $this->addSql('DROP TABLE couleur');
        $this->addSql('DROP TABLE produits_taille');
        $this->addSql('DROP TABLE produits_couleur');
        $this->addSql('DROP TABLE taille');
    }
}
