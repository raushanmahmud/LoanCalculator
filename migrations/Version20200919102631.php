<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200919102631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE loan (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, start_date DATE NOT NULL, amount DOUBLE PRECISION NOT NULL, months INTEGER NOT NULL, yearly_interest_rate DOUBLE PRECISION NOT NULL, title CLOB NOT NULL, body CLOB DEFAULT NULL)');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, loan_id INTEGER NOT NULL, scheduled_date DATE NOT NULL, full_amount_for_pay DOUBLE PRECISION NOT NULL, interest_amount_for_pay DOUBLE PRECISION NOT NULL, base_amount_for_pay DOUBLE PRECISION NOT NULL, remaining_base_amount_before_pay DOUBLE PRECISION NOT NULL, remaining_base_amount_after_pay DOUBLE PRECISION NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6D28840DCE73868F ON payment (loan_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE loan');
        $this->addSql('DROP TABLE payment');
    }
}
