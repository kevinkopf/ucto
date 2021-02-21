<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20000000000000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Database Primary Initialization';
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
        $this->addSql('CREATE TABLE statements_vat_inspectional (id INT AUTO_INCREMENT NOT NULL, sheet_a1_id INT NOT NULL, sheet_a2_id INT NOT NULL, sheet_a3_id INT NOT NULL, sheet_a4_id INT NOT NULL, sheet_a5_id INT NOT NULL, sheet_b1_id INT NOT NULL, sheet_b2_id INT NOT NULL, sheet_b3_id INT NOT NULL, created_at DATE NOT NULL, covering_month VARCHAR(2) NOT NULL, covering_year VARCHAR(4) NOT NULL, UNIQUE INDEX UNIQ_47638B74D0B238AF (sheet_a1_id), UNIQUE INDEX UNIQ_47638B74C2079741 (sheet_a2_id), UNIQUE INDEX UNIQ_47638B747ABBF024 (sheet_a3_id), UNIQUE INDEX UNIQ_47638B74E76CC89D (sheet_a4_id), UNIQUE INDEX UNIQ_47638B745FD0AFF8 (sheet_a5_id), UNIQUE INDEX UNIQ_47638B749712427F (sheet_b1_id), UNIQUE INDEX UNIQ_47638B7485A7ED91 (sheet_b2_id), UNIQUE INDEX UNIQ_47638B743D1B8AF4 (sheet_b3_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statements_vat_inspectional_sheets (id INT AUTO_INCREMENT NOT NULL, amount VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statements_vat_inspectional_sheets_containing_records (sheet_id INT NOT NULL, record_id INT NOT NULL, INDEX IDX_5B9C197F8B1206A5 (sheet_id), UNIQUE INDEX UNIQ_5B9C197F4DFD750C (record_id), PRIMARY KEY(sheet_id, record_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statements_vat_inspectional_sheets_records (id INT AUTO_INCREMENT NOT NULL, transaction_id INT NOT NULL, amount VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE statements_vat_inspectional_sheets_records ADD CONSTRAINT UNIQ_529CA5082FC0CB0F UNIQUE INDEX(id, transaction_id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B74D0B238AF FOREIGN KEY (sheet_a1_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B74C2079741 FOREIGN KEY (sheet_a2_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B747ABBF024 FOREIGN KEY (sheet_a3_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B74E76CC89D FOREIGN KEY (sheet_a4_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B745FD0AFF8 FOREIGN KEY (sheet_a5_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B749712427F FOREIGN KEY (sheet_b1_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B7485A7ED91 FOREIGN KEY (sheet_b2_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional ADD CONSTRAINT FK_47638B743D1B8AF4 FOREIGN KEY (sheet_b3_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional_sheets_containing_records ADD CONSTRAINT FK_5B9C197F8B1206A5 FOREIGN KEY (sheet_id) REFERENCES statements_vat_inspectional_sheets (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional_sheets_containing_records ADD CONSTRAINT FK_5B9C197F4DFD750C FOREIGN KEY (record_id) REFERENCES statements_vat_inspectional_sheets_records (id)');
        $this->addSql('ALTER TABLE statements_vat_inspectional_sheets_records ADD CONSTRAINT FK_529CA5082FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transactions (id)');
        $this->addSql('ALTER TABLE statements_trial_balances_records RENAME INDEX idx_e20d19049b6b5fba TO IDX_E0782B409B6B5FBA');
        $this->addSql('ALTER TABLE statements_trial_balances_records RENAME INDEX idx_e20d190433dd6f84 TO IDX_E0782B4033DD6F84');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B74D0B238AF');
        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B74C2079741');
        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B747ABBF024');
        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B74E76CC89D');
        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B745FD0AFF8');
        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B749712427F');
        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B7485A7ED91');
        $this->addSql('ALTER TABLE statements_vat_inspectional DROP FOREIGN KEY FK_47638B743D1B8AF4');
        $this->addSql('ALTER TABLE statements_vat_inspectional_sheets_containing_records DROP FOREIGN KEY FK_5B9C197F8B1206A5');
        $this->addSql('ALTER TABLE statements_vat_inspectional_sheets_containing_records DROP FOREIGN KEY FK_5B9C197F4DFD750C');
        $this->addSql('DROP TABLE statements_vat_inspectional');
        $this->addSql('DROP TABLE statements_vat_inspectional_sheets');
        $this->addSql('DROP TABLE statements_vat_inspectional_sheets_containing_records');
        $this->addSql('DROP TABLE statements_vat_inspectional_sheets_records');
        $this->addSql('ALTER TABLE statements_trial_balances_records RENAME INDEX idx_e0782b4033dd6f84 TO IDX_E20D190433DD6F84');
        $this->addSql('ALTER TABLE statements_trial_balances_records RENAME INDEX idx_e0782b409b6b5fba TO IDX_E20D19049B6B5FBA');
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
