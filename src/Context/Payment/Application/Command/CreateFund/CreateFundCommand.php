<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\CreateFund;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\FundId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Entity\CashItems;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\CashItemId;
use VM\Shared\Application\Bus\Command\Command;

class CreateFundCommand extends Command
{
    private const ID = 'id';
    private const VENDING_MACHINE_ID = 'vending_machine_id';
    private const CASH_ITEMS = 'cash_items';

    public static function create(
        string $id,
        string $vendingMachineId,
        array $cashItems,
    ): self {
        return new self([
            self::ID => $id,
            self::VENDING_MACHINE_ID => $vendingMachineId,
            self::CASH_ITEMS => $cashItems,
        ]);
    }

    public function id(): FundId
    {
        return FundId::fromString($this->get(self::ID));
    }

    public function vendingMachineId(): VendingMachineId
    {
        return VendingMachineId::fromString($this->get(self::VENDING_MACHINE_ID));
    }

    public function cashItems(): CashItems
    {
        $prescriptionItems = array_map(
            fn ($item) => $this->createCashItem($item),
            $this->get(self::CASH_ITEMS)
        );

        return CashItems::create($prescriptionItems);
    }

    private function createCashItem(array $item): CashItem
    {
        return CashItem::create(
            CashItemId::fromString($item['id']),
            CurrencyId::fromString($item['currencyId']),
            Amount::fromInt($item['amount'])
        );
    }

    public static function messageName(): string
    {
        return 'command.payment.create_fund';
    }
}
