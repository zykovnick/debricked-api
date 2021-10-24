<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211024100707 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql("
            create table vuln_files
                (
                    id integer
                        constraint vuln_files_pk
                            primary key autoincrement,
                    ci_upload_id int,
                    upload_programs_id int,
                    status int
                );
        ");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("drop table vuln_files ");
    }
}

