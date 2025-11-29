<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetCash;


use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Entity\CashItem;

final class GetCashQueryResponseConverter
{
    public function __invoke(Cash $cash): GetCashQueryResponse
    {
        return new GetCashQueryResponse([
            GetCashQueryResponse::ID => $cash->id()->value(),
            GetCashQueryResponse::VENDING_MACHINE_ID => $cash->vendingMachineId()->value(),
            GetCashQueryResponse::CASH_ITEMS => array_map($this->buildCashItemFn(), $cash->cashItems()->toArray()),
        ]);
    }

    public function buildCashItemFn(): callable
    {
        return fn (CashItem $cashItem) => [
            GetCashQueryResponse::CASH_ITEM_ID => $cashItem->id()->value(),
            GetCashQueryResponse::CURRENCY_ID => $cashItem->currencyId()->value(),
            GetCashQueryResponse::AMOUNT => $cashItem->amount()->value(),
        ];
    }
}
