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
final class Version20190223094033 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('CREATE SEQUENCE anthill_company_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE anthill_vacancy_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('
            CREATE TABLE anthill_company_history (
                id INT NOT NULL, 
                action VARCHAR(8) NOT NULL, 
                logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                object_id VARCHAR(64) DEFAULT NULL, 
                object_class VARCHAR(255) NOT NULL, 
                version INT NOT NULL, 
                data TEXT DEFAULT NULL, 
                username VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX anthill_company_history_object_class_ix ON anthill_company_history (object_class)');
        $this->addSql('CREATE INDEX anthill_company_history_logged_at_ix ON anthill_company_history (logged_at)');
        $this->addSql('CREATE INDEX anthill_company_history_username_ix ON anthill_company_history (username)');
        $this->addSql('
            CREATE INDEX 
                anthill_company_history_object_id_object_class_version_ix
            ON 
                anthill_company_history (object_id, object_class, version)
        ');
        $this->addSql('COMMENT ON COLUMN anthill_company_history.data IS \'(DC2Type:array)\'');

        $this->addSql('
            CREATE TABLE anthill_vacancy_history (
                id INT NOT NULL, 
                action VARCHAR(8) NOT NULL, 
                logged_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
                object_id VARCHAR(64) DEFAULT NULL, 
                object_class VARCHAR(255) NOT NULL, 
                version INT NOT NULL, 
                data TEXT DEFAULT NULL, 
                username VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            )
        ');
        $this->addSql('CREATE INDEX anthill_vacancy_history_object_class_ix ON anthill_vacancy_history (object_class)');
        $this->addSql('CREATE INDEX anthill_vacancy_history_logged_at_ix ON anthill_vacancy_history (logged_at)');
        $this->addSql('CREATE INDEX anthill_vacancy_history_username_ix ON anthill_vacancy_history (username)');
        $this->addSql('
            CREATE INDEX 
                anthill_vacancy_history_object_id_object_class_version_ix
            ON 
                anthill_vacancy_history (object_id, object_class, version)
        ');
        $this->addSql('COMMENT ON COLUMN anthill_vacancy_history.data IS \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('DROP SEQUENCE anthill_company_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE anthill_vacancy_history_id_seq CASCADE');
        $this->addSql('DROP TABLE anthill_company_history');
        $this->addSql('DROP TABLE anthill_vacancy_history');
    }
}
