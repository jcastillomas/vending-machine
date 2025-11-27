<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251126234616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'WARNING: this migration will fill data, this is only for the project Goal, migrations should not fill data.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO vending_machine.vending_machine (id, created_at, updated_at) VALUES ('fcbdd279-52f8-4428-87f4-1e9544b9ae1c', '2025-11-26 23:42:56', null);");
        $this->addSql("
            INSERT INTO vending_machine.product (name, value, created_at, updated_at, id) VALUES ('Juice', '1.00', '2025-11-26 23:42:56', null, '9bade147-51fb-489e-a967-5ff8093db877');
            INSERT INTO vending_machine.product (name, value, created_at, updated_at, id) VALUES ('Soda', '1.50', '2025-11-26 23:42:56', null, 'a0a59ca7-dce9-400d-8d29-e17a01f4c72c');
            INSERT INTO vending_machine.product (name, value, created_at, updated_at, id) VALUES ('Water', '0.65', '2025-11-26 23:42:56', null, 'b6eda59d-4774-4467-a1b5-56dd50c903ff');
        ");
        $this->addSql("INSERT INTO vending_machine.stock (vending_machine_id, created_at, updated_at, id) VALUES ('fcbdd279-52f8-4428-87f4-1e9544b9ae1c', '2025-11-26 23:42:56', null, 'ed943bb3-8947-4806-b71d-015f65a047d2');");
        $this->addSql("
            INSERT INTO vending_machine.stock_item (product_id, amount, created_at, updated_at, id) VALUES ('9bade147-51fb-489e-a967-5ff8093db877', 3, '2025-11-26 23:42:56', null, '08767be8-a0ac-4275-ba4b-a094ce846198');
            INSERT INTO vending_machine.stock_item (product_id, amount, created_at, updated_at, id) VALUES ('a0a59ca7-dce9-400d-8d29-e17a01f4c72c', 3, '2025-11-26 23:42:56', null, '1c7c0d0f-fddf-46f7-bbf8-dbe44c6d6405');
            INSERT INTO vending_machine.stock_item (product_id, amount, created_at, updated_at, id) VALUES ('b6eda59d-4774-4467-a1b5-56dd50c903ff', 3, '2025-11-26 23:42:56', null, '592ee815-04f7-416f-b9d2-e21f42dfd832');
        ");
        $this->addSql("
            INSERT INTO vending_machine.stock_stock_item (stock_id, stock_item_id) VALUES ('ed943bb3-8947-4806-b71d-015f65a047d2', '08767be8-a0ac-4275-ba4b-a094ce846198');
            INSERT INTO vending_machine.stock_stock_item (stock_id, stock_item_id) VALUES ('ed943bb3-8947-4806-b71d-015f65a047d2', '1c7c0d0f-fddf-46f7-bbf8-dbe44c6d6405');
            INSERT INTO vending_machine.stock_stock_item (stock_id, stock_item_id) VALUES ('ed943bb3-8947-4806-b71d-015f65a047d2', '592ee815-04f7-416f-b9d2-e21f42dfd832');
        ");
        $this->addSql("
            INSERT INTO vending_machine.currency (value, kind, created_at, updated_at, id) VALUES ('1.00', 'EUR', '2025-11-26 23:42:56', null, '7c85277c-12d7-4f34-a03b-533e7f4b6c03');
            INSERT INTO vending_machine.currency (value, kind, created_at, updated_at, id) VALUES ('0.05', 'EUR', '2025-11-26 23:42:56', null, '995e9deb-da13-48e0-abaa-0198b9f923e1');
            INSERT INTO vending_machine.currency (value, kind, created_at, updated_at, id) VALUES ('0.10', 'EUR', '2025-11-26 23:42:56', null, 'e62ae603-0131-4406-acef-b9a825eb1755');
            INSERT INTO vending_machine.currency (value, kind, created_at, updated_at, id) VALUES ('0.25', 'EUR', '2025-11-26 23:42:56', null, 'f11d5711-6d26-4082-aac2-ed873ebe8282');
        ");
        $this->addSql("INSERT INTO vending_machine.cash (vending_machine_id, created_at, updated_at, id) VALUES ('fcbdd279-52f8-4428-87f4-1e9544b9ae1c', '2025-11-26 23:42:56', null, '295e759c-c8d6-4b2d-9cc6-639ca3dbd346');");
        $this->addSql("
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('7c85277c-12d7-4f34-a03b-533e7f4b6c03', 5, '2025-11-26 23:42:56', null, 'b618cf89-cfef-41bf-995e-1de483e13e66');
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('995e9deb-da13-48e0-abaa-0198b9f923e1', 5, '2025-11-26 23:42:56', null, 'bbad4562-91a1-4982-8034-7c0658eecb5c');
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('e62ae603-0131-4406-acef-b9a825eb1755', 5, '2025-11-26 23:42:56', null, 'ca0a243b-bc4c-46fa-8614-8f29ec3eedda');
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('f11d5711-6d26-4082-aac2-ed873ebe8282', 5, '2025-11-26 23:42:56', null, 'cfaa19e9-ac21-4d51-abd2-db5997595dcc');
        ");
        $this->addSql("
            INSERT INTO vending_machine.cash_cash_item (cash_id, cash_item_id) VALUES ('295e759c-c8d6-4b2d-9cc6-639ca3dbd346', 'b618cf89-cfef-41bf-995e-1de483e13e66');
            INSERT INTO vending_machine.cash_cash_item (cash_id, cash_item_id) VALUES ('295e759c-c8d6-4b2d-9cc6-639ca3dbd346', 'bbad4562-91a1-4982-8034-7c0658eecb5c');
            INSERT INTO vending_machine.cash_cash_item (cash_id, cash_item_id) VALUES ('295e759c-c8d6-4b2d-9cc6-639ca3dbd346', 'ca0a243b-bc4c-46fa-8614-8f29ec3eedda');
            INSERT INTO vending_machine.cash_cash_item (cash_id, cash_item_id) VALUES ('295e759c-c8d6-4b2d-9cc6-639ca3dbd346', 'cfaa19e9-ac21-4d51-abd2-db5997595dcc');
        ");
        $this->addSql("INSERT INTO vending_machine.fund (vending_machine_id, created_at, updated_at, id) VALUES ('9f027cde-0868-4854-a9e5-857866e9302c', '2025-11-26 23:42:56', null, 'df2dbd0e-d848-48e3-8781-cc8ad6b42f7a');");
        $this->addSql("
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('7c85277c-12d7-4f34-a03b-533e7f4b6c03', 0, '2025-11-26 23:42:56', null, '8da76373-6591-44d4-8789-e71fb8424870');
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('995e9deb-da13-48e0-abaa-0198b9f923e1', 0, '2025-11-26 23:42:56', null, '5a70c974-f37b-4304-9de7-2b73846cf70b');
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('e62ae603-0131-4406-acef-b9a825eb1755', 0, '2025-11-26 23:42:56', null, '8a4175e0-bb18-40b9-9e7c-ed313453d2e4');
            INSERT INTO vending_machine.cash_item (currency_id, amount, created_at, updated_at, id) VALUES ('f11d5711-6d26-4082-aac2-ed873ebe8282', 0, '2025-11-26 23:42:56', null, 'c673ad3d-a79d-4a9c-989a-adcca3c4bc78');
        ");
        $this->addSql("
            INSERT INTO vending_machine.fund_cash_item (fund_id, cash_item_id) VALUES ('df2dbd0e-d848-48e3-8781-cc8ad6b42f7a', '8da76373-6591-44d4-8789-e71fb8424870');
            INSERT INTO vending_machine.fund_cash_item (fund_id, cash_item_id) VALUES ('df2dbd0e-d848-48e3-8781-cc8ad6b42f7a', '5a70c974-f37b-4304-9de7-2b73846cf70b');
            INSERT INTO vending_machine.fund_cash_item (fund_id, cash_item_id) VALUES ('df2dbd0e-d848-48e3-8781-cc8ad6b42f7a', '8a4175e0-bb18-40b9-9e7c-ed313453d2e4');
            INSERT INTO vending_machine.fund_cash_item (fund_id, cash_item_id) VALUES ('df2dbd0e-d848-48e3-8781-cc8ad6b42f7a', 'c673ad3d-a79d-4a9c-989a-adcca3c4bc78');
        ");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
