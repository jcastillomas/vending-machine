<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126222817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fund (vending_machine_id VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, id VARCHAR(64) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE fund_cash_item (fund_id VARCHAR(64) NOT NULL, cash_item_id VARCHAR(64) NOT NULL, INDEX IDX_AF9A0D7B25A38F89 (fund_id), UNIQUE INDEX UNIQ_AF9A0D7B4C834002 (cash_item_id), PRIMARY KEY (fund_id, cash_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE fund_cash_item ADD CONSTRAINT FK_AF9A0D7B25A38F89 FOREIGN KEY (fund_id) REFERENCES fund (id)');
        $this->addSql('ALTER TABLE fund_cash_item ADD CONSTRAINT FK_AF9A0D7B4C834002 FOREIGN KEY (cash_item_id) REFERENCES cash_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fund_cash_item DROP FOREIGN KEY FK_AF9A0D7B25A38F89');
        $this->addSql('ALTER TABLE fund_cash_item DROP FOREIGN KEY FK_AF9A0D7B4C834002');
        $this->addSql('DROP TABLE fund');
        $this->addSql('DROP TABLE fund_cash_item');
    }
}
