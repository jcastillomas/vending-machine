<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126215043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cash (vending_machine_id VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, id VARCHAR(64) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE cash_cash_item (cash_id VARCHAR(64) NOT NULL, cash_item_id VARCHAR(64) NOT NULL, INDEX IDX_C72AD18F3D7A0C28 (cash_id), UNIQUE INDEX UNIQ_C72AD18F4C834002 (cash_item_id), PRIMARY KEY (cash_id, cash_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE cash_item (currency_id VARCHAR(64) NOT NULL, amount INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, id VARCHAR(64) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE cash_cash_item ADD CONSTRAINT FK_C72AD18F3D7A0C28 FOREIGN KEY (cash_id) REFERENCES cash (id)');
        $this->addSql('ALTER TABLE cash_cash_item ADD CONSTRAINT FK_C72AD18F4C834002 FOREIGN KEY (cash_item_id) REFERENCES cash_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cash_cash_item DROP FOREIGN KEY FK_C72AD18F3D7A0C28');
        $this->addSql('ALTER TABLE cash_cash_item DROP FOREIGN KEY FK_C72AD18F4C834002');
        $this->addSql('DROP TABLE cash');
        $this->addSql('DROP TABLE cash_cash_item');
        $this->addSql('DROP TABLE cash_item');
    }
}
