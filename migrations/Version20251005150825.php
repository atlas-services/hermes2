<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251005150825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, section_id INTEGER DEFAULT NULL, name VARCHAR(25) NOT NULL, content CLOB DEFAULT NULL, file_name VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, active BOOLEAN NOT NULL, position INTEGER DEFAULT NULL, start_published_at DATETIME DEFAULT NULL, end_published_at DATETIME DEFAULT NULL, CONSTRAINT FK_5A8A6C8DD823E37A FOREIGN KEY (section_id) REFERENCES section (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DD823E37A ON post (section_id)');
        $this->addSql('CREATE TABLE section (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, template_id INTEGER DEFAULT NULL, template2_id INTEGER DEFAULT NULL, menu INTEGER DEFAULT NULL, template_width INTEGER DEFAULT NULL, template_bgcolor VARCHAR(255) DEFAULT NULL, template_nb_col INTEGER DEFAULT NULL, template_image_filter VARCHAR(255) DEFAULT NULL, template2_width INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, position INTEGER DEFAULT NULL, name VARCHAR(25) NOT NULL, CONSTRAINT FK_2D737AEF5DA0FB8 FOREIGN KEY (template_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2D737AEF16663CC FOREIGN KEY (template2_id) REFERENCES template (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2D737AEF7D053A93 FOREIGN KEY (menu) REFERENCES menu (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2D737AEF5DA0FB8 ON section (template_id)');
        $this->addSql('CREATE INDEX IDX_2D737AEF16663CC ON section (template2_id)');
        $this->addSql('CREATE INDEX IDX_2D737AEF7D053A93 ON section (menu)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE section');
    }
}
