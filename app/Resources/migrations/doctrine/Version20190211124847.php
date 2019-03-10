<?php

/*
 * This file is part of the Veslo project <https://github.com/symfony-doge/veslo>.
 *
 * (C) 2019 Pavel Petrov <itnelo@gmail.com>.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license https://opensource.org/licenses/GPL-3.0 GPL-3.0
 */

declare(strict_types=1);

namespace Veslo\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190211124847 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SEQUENCE anthill_vacancy_roadmap_headhunter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE anthill_vacancy_roadmap_headhunter (
                id INT NOT NULL, 
                configuration_key VARCHAR(255) NOT NULL, 
                url VARCHAR(255) NOT NULL, 
                text VARCHAR(255) NOT NULL, 
                area INT NOT NULL, 
                date_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                date_to TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                order_by VARCHAR(255) NOT NULL, 
                per_page INT NOT NULL, 
                received INT NOT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql(
            'CREATE UNIQUE INDEX UNIQ_5F46689693F6AF1 ON anthill_vacancy_roadmap_headhunter (configuration_key)'
        );
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.id IS \'Parameters record identifier\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.configuration_key IS \'Roadmap configuration key to which parameters belongs to\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.url IS \'Vacancy website URL\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.text IS \'Search query text\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.area IS \'Vacancy area\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.date_from IS \'Publication date from\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.date_to IS \'Publication date to\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.order_by IS \'Order by criteria\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.per_page IS \'Number of vacancies to fetch for a single page\'');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_roadmap_headhunter.received IS \'Vacancies received during specified publication date range\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('DROP SEQUENCE anthill_vacancy_roadmap_headhunter_id_seq CASCADE');
        $this->addSql('DROP TABLE anthill_vacancy_roadmap_headhunter');
    }
}
