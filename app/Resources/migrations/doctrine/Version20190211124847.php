<?php

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
        $this->addSql(
            'CREATE TABLE anthill_vacancy_roadmap_headhunter (
          id INT NOT NULL, 
          configuration_key VARCHAR(255) NOT NULL, 
          text VARCHAR(255) NOT NULL, 
          area INT NOT NULL, 
          date_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          date_to TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
          order_by VARCHAR(255) NOT NULL, 
          per_page INT NOT NULL, 
          received INT NOT NULL, 
          PRIMARY KEY(id)
        )'
        );
        $this->addSql(
            'CREATE UNIQUE INDEX UNIQ_5F46689693F6AF1 ON anthill_vacancy_roadmap_headhunter (configuration_key)'
        );
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
