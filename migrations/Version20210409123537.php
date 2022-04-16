<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210409123537 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE jeu ADD CONSTRAINT FK_82E48DB5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_82E48DB5A76ED395 ON jeu (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jeu DROP FOREIGN KEY FK_82E48DB5A76ED395');
        $this->addSql('DROP INDEX IDX_82E48DB5A76ED395 ON jeu');
        $this->addSql('ALTER TABLE jeu DROP user_id');
    }
}
