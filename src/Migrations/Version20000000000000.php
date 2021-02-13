<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20000000000000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `accounts` (`id` int NOT NULL AUTO_INCREMENT, `type_id` int NOT NULL, `kind_id` int DEFAULT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `numeral` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`), KEY `IDX_CAC89EACC54C8C93` (`type_id`), KEY `IDX_CAC89EAC30602CA9` (`kind_id`), CONSTRAINT `FK_CAC89EAC30602CA9` FOREIGN KEY (`kind_id`) REFERENCES `accounts_kinds` (`id`), CONSTRAINT `FK_CAC89EACC54C8C93` FOREIGN KEY (`type_id`) REFERENCES `accounts_types` (`id`)) ENGINE=InnoDB AUTO_INCREMENT=242 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `accounts_analytical` (`id` int NOT NULL AUTO_INCREMENT, `account_id` int NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `numeral` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`), KEY `IDX_A24CE1DB9B6B5FBA` (`account_id`), CONSTRAINT `FK_A24CE1DB9B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`)) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `accounts_kinds` (`id` int NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `accounts_types` (`id` int NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `contacts` (`id` int NOT NULL AUTO_INCREMENT, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `registration_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `vat_number_prefix` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `vat_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `vat_payer` tinyint(1) NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `config_profile` (`id` int NOT NULL AUTO_INCREMENT, `tax_number` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL, `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, `vat_payer` tinyint(1) NOT NULL, `vat_number` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL, `file_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `transactions` (`id` int NOT NULL AUTO_INCREMENT, `contact_id` int NOT NULL, `description` longtext COLLATE utf8mb4_unicode_ci, `taxable_supply_date` date NOT NULL, `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL, PRIMARY KEY (`id`), KEY `IDX_EAA81A4CE7A1254A` (`contact_id`), CONSTRAINT `FK_EAA81A4CE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`)) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `transactions_rows` (`id` int NOT NULL AUTO_INCREMENT, `transaction_id` int NOT NULL, `debtors_account_id` int NOT NULL, `creditors_account_id` int NOT NULL, `debtors_account_analytical_id` int DEFAULT NULL, `creditors_account_analytical_id` int DEFAULT NULL, `description` longtext COLLATE utf8mb4_unicode_ci, `amount` int NOT NULL, PRIMARY KEY (`id`), KEY `IDX_86F391B72FC0CB0F` (`transaction_id`), KEY `IDX_86F391B7FE60C388` (`debtors_account_id`), KEY `IDX_86F391B72856DB47` (`creditors_account_id`), KEY `IDX_86F391B7C1EC5500` (`debtors_account_analytical_id`), KEY `IDX_86F391B75A9D8A69` (`creditors_account_analytical_id`), CONSTRAINT `FK_86F391B72856DB47` FOREIGN KEY (`creditors_account_id`) REFERENCES `accounts` (`id`), CONSTRAINT `FK_86F391B72FC0CB0F` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`), CONSTRAINT `FK_86F391B75A9D8A69` FOREIGN KEY (`creditors_account_analytical_id`) REFERENCES `accounts_analytical` (`id`), CONSTRAINT `FK_86F391B7C1EC5500` FOREIGN KEY (`debtors_account_analytical_id`) REFERENCES `accounts_analytical` (`id`), CONSTRAINT `FK_86F391B7FE60C388` FOREIGN KEY (`debtors_account_id`) REFERENCES `accounts` (`id`)) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;');
        $this->addSql('CREATE TABLE `statements_trial_balances` (`id` INT AUTO_INCREMENT NOT NULL, `compiled_to_date` DATE NOT NULL, `compiled_at` DATE NOT NULL, PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `statements_trial_balances_records` (`id` INT AUTO_INCREMENT NOT NULL, `account_id` INT NOT NULL, `trial_balance_id` INT NOT NULL, `opening_balance` VARCHAR(255) NOT NULL, `debtor_balance` VARCHAR(255) NOT NULL, `creditor_balance` VARCHAR(255) NOT NULL, `closing_balance` VARCHAR(255) NOT NULL, INDEX IDX_E20D19049B6B5FBA (`account_id`), INDEX IDX_E20D190433DD6F84 (`trial_balance_id`), PRIMARY KEY(`id`)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `statements_trial_balances_records` ADD CONSTRAINT FK_E20D19049B6B5FBA FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`)');
        $this->addSql('ALTER TABLE `statements_trial_balances_records` ADD CONSTRAINT FK_E20D190433DD6F84 FOREIGN KEY (`trial_balance_id`) REFERENCES `trial_balance` (`id`)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `statements_trial_balances_records` DROP FOREIGN KEY FK_E20D190433DD6F84');
        $this->addSql('DROP TABLE `statements_trial_balances`');
        $this->addSql('DROP TABLE `statements_trial_balances_records`');
        $this->addSql('ALTER TABLE `transactions_rows` DROP FOREIGN KEY FK_86F391B7FE60C388');
        $this->addSql('ALTER TABLE `transactions_rows` DROP FOREIGN KEY FK_86F391B7C1EC5500');
        $this->addSql('ALTER TABLE `transactions_rows` DROP FOREIGN KEY FK_86F391B75A9D8A69');
        $this->addSql('ALTER TABLE `transactions_rows` DROP FOREIGN KEY FK_86F391B72FC0CB0F');
        $this->addSql('ALTER TABLE `transactions_rows` DROP FOREIGN KEY FK_86F391B72856DB47');
        $this->addSql('ALTER TABLE `transactions` DROP FOREIGN KEY FK_EAA81A4CE7A1254A');
        $this->addSql('ALTER TABLE `accounts_analytical` DROP FOREIGN KEY FK_A24CE1DB9B6B5FBA');
        $this->addSql('ALTER TABLE `accounts` DROP FOREIGN KEY FK_CAC89EACC54C8C93');
        $this->addSql('ALTER TABLE `accounts` DROP FOREIGN KEY FK_CAC89EAC30602CA9');
        $this->addSql('DROP TABLE `transactions_rows`');
        $this->addSql('DROP TABLE `transactions`');
        $this->addSql('DROP TABLE `config_profile`');
        $this->addSql('DROP TABLE `contacts`');
        $this->addSql('DROP TABLE `accounts_types`');
        $this->addSql('DROP TABLE `accounts_kinds`');
        $this->addSql('DROP TABLE `accounts_analytical`');
        $this->addSql('DROP TABLE `accounts`');
    }
}
