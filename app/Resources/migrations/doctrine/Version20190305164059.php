<?php

declare(strict_types=1);

namespace Veslo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190305164059 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SEQUENCE sanity_vacancy_tag_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sanity_vacancy_index_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sanity_vacancy_tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE sanity_vacancy_tag_group (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                description TEXT NOT NULL, 
                color VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CC778395E237E06 ON sanity_vacancy_tag_group (name)');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag_group.id IS \'Identifier for group of sanity tags\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag_group.name IS \'Sanity tags group name\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag_group.description IS \'Sanity tags group description\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag_group.color IS \'Sanity tags group color\'');

        $this->addSql('
            CREATE TABLE sanity_vacancy_index (
                id INT NOT NULL, 
                vacancy_id INT NOT NULL, 
                value NUMERIC(5, 2) NOT NULL, 
                indexation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_63CA8DC6433B78C4 ON sanity_vacancy_index (vacancy_id)');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_index.id IS \'Sanity index identifier\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_index.vacancy_id IS \'Vacancy identifier to which sanity index belongs to\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_index.value IS \'Sanity index value, from 0.00 to 100.00\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_index.indexation_date IS \'Date and time when sanity index was created\'');

        $this->addSql('
            CREATE TABLE sanity_vacancy_tag (
                id INT NOT NULL, 
                group_id INT DEFAULT NULL, 
                name VARCHAR(255) NOT NULL, 
                description TEXT NOT NULL, 
                color VARCHAR(255) NOT NULL, 
                image_url VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_82E4EFE85E237E06 ON sanity_vacancy_tag (name)');
        $this->addSql('CREATE INDEX IDX_82E4EFE8FE54D947 ON sanity_vacancy_tag (group_id)');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag.id IS \'Sanity tag identifier\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag.group_id IS \'Identifier for group of sanity tags\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag.name IS \'Sanity tag name\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag.description IS \'Sanity tag description\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag.color IS \'Sanity tag color\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag.image_url IS \'Sanity tag image URL\'');

        $this->addSql('
            CREATE TABLE sanity_vacancy_index_sanity_vacancy_tag (
                index_id INT NOT NULL, 
                tag_id INT NOT NULL, 
                PRIMARY KEY(index_id, tag_id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_F048F61784337261 ON sanity_vacancy_index_sanity_vacancy_tag (index_id)');
        $this->addSql('CREATE INDEX IDX_F048F617BAD26311 ON sanity_vacancy_index_sanity_vacancy_tag (tag_id)');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_index_sanity_vacancy_tag.index_id IS \'Sanity index identifier\'');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_index_sanity_vacancy_tag.tag_id IS \'Sanity tag identifier\'');

        $this->addSql('
            ALTER TABLE 
                sanity_vacancy_index_sanity_vacancy_tag 
            ADD CONSTRAINT
                FK_F048F61784337261
            FOREIGN KEY (index_id) REFERENCES sanity_vacancy_index (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE 
                sanity_vacancy_index_sanity_vacancy_tag 
            ADD CONSTRAINT 
                FK_F048F617BAD26311 
            FOREIGN KEY (tag_id) REFERENCES sanity_vacancy_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
        $this->addSql('
            ALTER TABLE 
                sanity_vacancy_tag 
            ADD CONSTRAINT 
                FK_82E4EFE8FE54D947 
            FOREIGN KEY (group_id) REFERENCES sanity_vacancy_tag_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE sanity_vacancy_tag DROP CONSTRAINT FK_82E4EFE8FE54D947');
        $this->addSql('ALTER TABLE sanity_vacancy_index_sanity_vacancy_tag DROP CONSTRAINT FK_F048F61784337261');
        $this->addSql('ALTER TABLE sanity_vacancy_index_sanity_vacancy_tag DROP CONSTRAINT FK_F048F617BAD26311');
        $this->addSql('DROP SEQUENCE sanity_vacancy_tag_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sanity_vacancy_index_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sanity_vacancy_tag_id_seq CASCADE');
        $this->addSql('DROP TABLE sanity_vacancy_index_sanity_vacancy_tag');
        $this->addSql('DROP TABLE sanity_vacancy_tag');
        $this->addSql('DROP TABLE sanity_vacancy_index');
        $this->addSql('DROP TABLE sanity_vacancy_tag_group');
    }
}
