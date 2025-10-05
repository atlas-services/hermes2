<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251005131751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, active BOOLEAN NOT NULL, type VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(25) NOT NULL, summary VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__menu AS SELECT id, parent_id, name, position, active FROM menu');
        $this->addSql('DROP TABLE menu');
        $this->addSql('CREATE TABLE menu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, name VARCHAR(25) NOT NULL, position INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO menu (id, parent_id, name, position, active) SELECT id, parent_id, name, position, active FROM __temp__menu');
        $this->addSql('DROP TABLE __temp__menu');
        $this->addSql('CREATE INDEX IDX_7D053A93727ACA70 ON menu (parent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE template');
        $this->addSql('CREATE TEMPORARY TABLE __temp__menu AS SELECT id, parent_id, active, name, position FROM menu');
        $this->addSql('DROP TABLE menu');
        $this->addSql('CREATE TABLE menu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, name VARCHAR(25) NOT NULL, position INTEGER NOT NULL, slug VARCHAR(30) NOT NULL, CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO menu (id, parent_id, active, name, position) SELECT id, parent_id, active, name, position FROM __temp__menu');
        $this->addSql('DROP TABLE __temp__menu');
        $this->addSql('CREATE INDEX IDX_7D053A93727ACA70 ON menu (parent_id)');
    }
}
