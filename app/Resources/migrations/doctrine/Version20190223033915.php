<?php

declare(strict_types=1);

namespace Veslo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190223033915 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SEQUENCE anthill_company_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE anthill_vacancy_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE anthill_vacancy_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE anthill_company (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                url VARCHAR(255) DEFAULT NULL, 
                logo_url VARCHAR(255) DEFAULT NULL,
                synchronization_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                deletion_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3B8342095E237E06 ON anthill_company (name)');
        $this->addSql('COMMENT ON COLUMN anthill_company.id IS \'Company identifier\'');
        $this->addSql('COMMENT ON COLUMN anthill_company.name IS \'Company name\'');
        $this->addSql('COMMENT ON COLUMN anthill_company.url IS \'Company website URL\'');
        $this->addSql('COMMENT ON COLUMN anthill_company.logo_url IS \'Company logo URL\'');
        $this->addSql('COMMENT ON COLUMN anthill_company.synchronization_date IS \'Last time when company data has been fetched from external job website\'');
        $this->addSql('COMMENT ON COLUMN anthill_company.deletion_date IS \'Date when company has been deleted\'');

        $this->addSql('
            CREATE TABLE anthill_vacancy_category (
                id INT NOT NULL, 
                name VARCHAR(255) NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_52D32B3F5E237E06 ON anthill_vacancy_category (name)');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_category.id IS \'Vacancy category identifier\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_category.name IS \'Vacancy category name\'');

        $this->addSql('
            CREATE TABLE anthill_vacancy (
                id INT NOT NULL,
                slug VARCHAR(255) NOT NULL,
                company_id INT DEFAULT NULL, 
                roadmap_name VARCHAR(255) NOT NULL, 
                external_identifier VARCHAR(255) NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                region_name VARCHAR(255) NOT NULL, 
                title VARCHAR(255) NOT NULL, 
                snippet TEXT DEFAULT NULL,
                text TEXT NOT NULL,
                salary_from INT DEFAULT NULL,
                salary_to INT DEFAULT NULL,
                salary_currency VARCHAR(255) DEFAULT NULL, 
                publication_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                synchronization_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                deletion_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_DD0827FB979B1AD6 ON anthill_vacancy (company_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DD0827FB989D9B62 ON anthill_vacancy (slug)');
        $this->addSql('
            CREATE UNIQUE INDEX 
                anthill_vacancy_roadmap_name_external_identifier_uq 
            ON 
                anthill_vacancy (roadmap_name, external_identifier)
        ');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.id IS \'Vacancy identifier\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.slug IS \'Alternative SEO-friendly vacancy identifier\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.company_id IS \'Company identifier\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.roadmap_name IS \'Roadmap name by which the vacancy has been fetched\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.external_identifier IS \'Unique vacancy identifier on provider\'\'s website\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.url IS \'Vacancy URL\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.region_name IS \'Vacancy region name\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.title IS \'Vacancy title\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.snippet IS \'Vacancy preview text\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.text IS \'Vacancy text\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.salary_from IS \'Vacancy salary amount from\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.salary_to IS \'Vacancy salary amount to\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.salary_currency IS \'Vacancy salary currency\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.publication_date IS \'Vacancy publication date\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.synchronization_date IS \'Last time when vacancy data has been fetched from external job website\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy.deletion_date IS \'Date when vacancy has been deleted\'');

        $this->addSql('
            CREATE TABLE anthill_vacancy_anthill_vacancy_category (
                vacancy_id INT NOT NULL, 
                category_id INT NOT NULL, 
                PRIMARY KEY(vacancy_id, category_id)
            )
        ');
        $this->addSql('CREATE INDEX IDX_EF8DA9A6433B78C4 ON anthill_vacancy_anthill_vacancy_category (vacancy_id)');
        $this->addSql('CREATE INDEX IDX_EF8DA9A612469DE2 ON anthill_vacancy_anthill_vacancy_category (category_id)');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_anthill_vacancy_category.vacancy_id IS \'Vacancy identifier\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_anthill_vacancy_category.category_id IS \'Vacancy category identifier\'');
        $this->addSql('
            ALTER TABLE 
                anthill_vacancy 
            ADD CONSTRAINT 
                FK_DD0827FB979B1AD6 
            FOREIGN KEY (company_id) REFERENCES anthill_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('
            ALTER TABLE 
                anthill_vacancy_anthill_vacancy_category 
            ADD CONSTRAINT 
                FK_EF8DA9A6433B78C4 
            FOREIGN KEY (vacancy_id) REFERENCES anthill_vacancy (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
        $this->addSql('
            ALTER TABLE 
                anthill_vacancy_anthill_vacancy_category 
            ADD CONSTRAINT
                FK_EF8DA9A612469DE2
            FOREIGN KEY (category_id) REFERENCES anthill_vacancy_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE'
        );
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE anthill_vacancy DROP CONSTRAINT FK_DD0827FB979B1AD6');
        $this->addSql('ALTER TABLE anthill_vacancy_anthill_vacancy_category DROP CONSTRAINT FK_EF8DA9A612469DE2');
        $this->addSql('ALTER TABLE anthill_vacancy_anthill_vacancy_category DROP CONSTRAINT FK_EF8DA9A6433B78C4');
        $this->addSql('DROP SEQUENCE anthill_company_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE anthill_vacancy_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE anthill_vacancy_id_seq CASCADE');
        $this->addSql('DROP TABLE anthill_company');
        $this->addSql('DROP TABLE anthill_vacancy_category');
        $this->addSql('DROP TABLE anthill_vacancy');
        $this->addSql('DROP TABLE anthill_vacancy_anthill_vacancy_category');
    }
}
