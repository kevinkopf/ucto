<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915203205 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE trial_balance (id INT AUTO_INCREMENT NOT NULL, compiled_to_date DATE NOT NULL, compiled_at DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trial_balance_record (id INT AUTO_INCREMENT NOT NULL, account_id INT NOT NULL, trial_balance_id INT NOT NULL, opening_balance VARCHAR(255) NOT NULL, debtor_balance VARCHAR(255) NOT NULL, creditor_balance VARCHAR(255) NOT NULL, closing_balance VARCHAR(255) NOT NULL, INDEX IDX_E20D19049B6B5FBA (account_id), INDEX IDX_E20D190433DD6F84 (trial_balance_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trial_balance_record ADD CONSTRAINT FK_E20D19049B6B5FBA FOREIGN KEY (account_id) REFERENCES accounts (id)');
        $this->addSql('ALTER TABLE trial_balance_record ADD CONSTRAINT FK_E20D190433DD6F84 FOREIGN KEY (trial_balance_id) REFERENCES trial_balance (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trial_balance_record DROP FOREIGN KEY FK_E20D190433DD6F84');
        $this->addSql('DROP TABLE trial_balance');
        $this->addSql('DROP TABLE trial_balance_record');
    }
}
