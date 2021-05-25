<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210524120834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE course_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE course (id INT NOT NULL, symbol_code VARCHAR(255) NOT NULL, course_type SMALLINT NOT NULL, cost DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, course_id INT DEFAULT NULL, username_id INT NOT NULL, operation_type SMALLINT NOT NULL, value DOUBLE PRECISION DEFAULT NULL, �validity TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D1591CC992 ON transaction (course_id)');
        $this->addSql('CREATE INDEX IDX_723705D1ED766068 ON transaction (username_id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1591CC992 FOREIGN KEY (course_id) REFERENCES course (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1ED766068 FOREIGN KEY (username_id) REFERENCES billing_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1591CC992');
        $this->addSql('DROP SEQUENCE course_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE transaction');
    }
}
