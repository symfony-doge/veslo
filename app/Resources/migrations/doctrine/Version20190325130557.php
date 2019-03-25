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
 * So, an approximate precision will be enough for our rating value field, we don't need to use a specific math
 * and string representation of float field for accuracy guarantees, it is redundant in this case
 *
 * @see https://www.doctrine-project.org/projects/doctrine-dbal/en/2.9/reference/types.html#float
 */
final class Version20190325130557 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE sanity_vacancy_index ALTER value TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE sanity_vacancy_index ALTER value DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'postgresql',
            'Migration can only be executed safely on \'postgresql\'.'
        );

        $this->addSql('ALTER TABLE sanity_vacancy_index ALTER value TYPE NUMERIC(5, 2)');
        $this->addSql('ALTER TABLE sanity_vacancy_index ALTER value DROP DEFAULT');
    }
}
