<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126234058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stock (vending_machine_id VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, id VARCHAR(64) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE stock_stock_item (stock_id VARCHAR(64) NOT NULL, stock_item_id VARCHAR(64) NOT NULL, INDEX IDX_A8488A76DCD6110 (stock_id), UNIQUE INDEX UNIQ_A8488A76BC942FD (stock_item_id), PRIMARY KEY (stock_id, stock_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE stock_item (product_id VARCHAR(64) NOT NULL, amount INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, id VARCHAR(64) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE stock_stock_item ADD CONSTRAINT FK_A8488A76DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE stock_stock_item ADD CONSTRAINT FK_A8488A76BC942FD FOREIGN KEY (stock_item_id) REFERENCES stock_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock_stock_item DROP FOREIGN KEY FK_A8488A76DCD6110');
        $this->addSql('ALTER TABLE stock_stock_item DROP FOREIGN KEY FK_A8488A76BC942FD');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE stock_stock_item');
        $this->addSql('DROP TABLE stock_item');
    }
}
