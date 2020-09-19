<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200919102904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_6D28840DCE73868F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, loan_id, scheduled_date, full_amount_for_pay, interest_amount_for_pay, base_amount_for_pay, remaining_base_amount_before_pay, remaining_base_amount_after_pay FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, loan_id INTEGER NOT NULL, scheduled_date DATE NOT NULL, full_amount_for_pay DOUBLE PRECISION NOT NULL, interest_amount_for_pay DOUBLE PRECISION NOT NULL, base_amount_for_pay DOUBLE PRECISION NOT NULL, remaining_base_amount_before_pay DOUBLE PRECISION NOT NULL, remaining_base_amount_after_pay DOUBLE PRECISION NOT NULL, CONSTRAINT FK_6D28840DCE73868F FOREIGN KEY (loan_id) REFERENCES loan (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO payment (id, loan_id, scheduled_date, full_amount_for_pay, interest_amount_for_pay, base_amount_for_pay, remaining_base_amount_before_pay, remaining_base_amount_after_pay) SELECT id, loan_id, scheduled_date, full_amount_for_pay, interest_amount_for_pay, base_amount_for_pay, remaining_base_amount_before_pay, remaining_base_amount_after_pay FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('CREATE INDEX IDX_6D28840DCE73868F ON payment (loan_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_6D28840DCE73868F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__payment AS SELECT id, loan_id, scheduled_date, full_amount_for_pay, interest_amount_for_pay, base_amount_for_pay, remaining_base_amount_before_pay, remaining_base_amount_after_pay FROM payment');
        $this->addSql('DROP TABLE payment');
        $this->addSql('CREATE TABLE payment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, loan_id INTEGER NOT NULL, scheduled_date DATE NOT NULL, full_amount_for_pay DOUBLE PRECISION NOT NULL, interest_amount_for_pay DOUBLE PRECISION NOT NULL, base_amount_for_pay DOUBLE PRECISION NOT NULL, remaining_base_amount_before_pay DOUBLE PRECISION NOT NULL, remaining_base_amount_after_pay DOUBLE PRECISION NOT NULL)');
        $this->addSql('INSERT INTO payment (id, loan_id, scheduled_date, full_amount_for_pay, interest_amount_for_pay, base_amount_for_pay, remaining_base_amount_before_pay, remaining_base_amount_after_pay) SELECT id, loan_id, scheduled_date, full_amount_for_pay, interest_amount_for_pay, base_amount_for_pay, remaining_base_amount_before_pay, remaining_base_amount_after_pay FROM __temp__payment');
        $this->addSql('DROP TABLE __temp__payment');
        $this->addSql('CREATE INDEX IDX_6D28840DCE73868F ON payment (loan_id)');
    }
}
