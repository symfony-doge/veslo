<?php

declare(strict_types=1);

namespace Veslo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307200722 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('
            CREATE TABLE sanity_vacancy_tag_group_translation (
                id SERIAL NOT NULL,
                object_id INT DEFAULT NULL,
                locale VARCHAR(8) NOT NULL,
                field VARCHAR(32) NOT NULL,
                content TEXT DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_5BE2AE7E232D562B ON sanity_vacancy_tag_group_translation (object_id)');
        $this->addSql('
            CREATE UNIQUE INDEX
                sanity_vacancy_tag_group_translation_locale_object_id_field_uq
            ON
                sanity_vacancy_tag_group_translation (locale, object_id, field)
        ');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag_group_translation.object_id IS \'Identifier for group of sanity tags\'');
        $this->addSql('
            ALTER TABLE
                sanity_vacancy_tag_group_translation
            ADD CONSTRAINT
                FK_5BE2AE7E232D562B
            FOREIGN KEY (object_id) REFERENCES sanity_vacancy_tag_group (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        ');

        $this->addSql('
            CREATE TABLE sanity_vacancy_tag_translation (
                id SERIAL NOT NULL,
                object_id INT DEFAULT NULL,
                locale VARCHAR(8) NOT NULL,
                field VARCHAR(32) NOT NULL,
                content TEXT DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_662A5024232D562B ON sanity_vacancy_tag_translation (object_id)');
        $this->addSql('
            CREATE UNIQUE INDEX
                sanity_vacancy_tag_translation_locale_object_id_field_uq
            ON 
                sanity_vacancy_tag_translation (locale, object_id, field)
        ');
        $this->addSql('COMMENT ON COLUMN sanity_vacancy_tag_translation.object_id IS \'Sanity tag identifier\'');
        $this->addSql('
            ALTER TABLE
                sanity_vacancy_tag_translation
            ADD CONSTRAINT
                FK_662A5024232D562B
            FOREIGN KEY (object_id) REFERENCES sanity_vacancy_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('DROP TABLE sanity_vacancy_tag_group_translation');
        $this->addSql('DROP TABLE sanity_vacancy_tag_translation');
    }
}
