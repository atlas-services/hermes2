<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251016123841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, published_at DATETIME NOT NULL, code VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, value VARCHAR(255) DEFAULT NULL, summary VARCHAR(255) DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, start_published_at DATETIME DEFAULT NULL, end_published_at DATETIME DEFAULT NULL, active BOOLEAN NOT NULL, position INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE menu (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, name VARCHAR(25) NOT NULL, slug VARCHAR(30) NOT NULL, position INTEGER DEFAULT NULL, CONSTRAINT FK_7D053A93727ACA70 FOREIGN KEY (parent_id) REFERENCES menu (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7D053A93727ACA70 ON menu (parent_id)');
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, template_id INTEGER DEFAULT NULL, template2_id INTEGER DEFAULT NULL, menu INTEGER DEFAULT NULL, template_width INTEGER DEFAULT NULL, template_bgcolor VARCHAR(255) DEFAULT NULL, template_nb_col INTEGER DEFAULT NULL, template_image_filter VARCHAR(255) DEFAULT NULL, template2_width INTEGER DEFAULT NULL, name VARCHAR(25) NOT NULL, content CLOB DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, active BOOLEAN NOT NULL, position INTEGER DEFAULT NULL, start_published_at DATETIME DEFAULT NULL, end_published_at DATETIME DEFAULT NULL, CONSTRAINT FK_5A8A6C8D5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5A8A6C8D16663CC FOREIGN KEY (template2_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_5A8A6C8D7D053A93 FOREIGN KEY (menu) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D5DA0FB8 ON post (template_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D16663CC ON post (template2_id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D7D053A93 ON post (menu)');
        $this->addSql('CREATE TABLE template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, active BOOLEAN NOT NULL, type VARCHAR(255) DEFAULT NULL, code VARCHAR(255) DEFAULT NULL, name VARCHAR(25) NOT NULL, summary VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE user');
    }
}
