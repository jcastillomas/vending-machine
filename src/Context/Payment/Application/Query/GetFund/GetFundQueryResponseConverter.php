<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetFund;


use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Entity\CashItem;

final class GetFundQueryResponseConverter
{
    public function __invoke(Fund $fund): GetFundQueryResponse
    {
        return new GetFundQueryResponse([
            GetFundQueryResponse::ID => $fund->id()->value(),
            GetFundQueryResponse::VENDING_MACHINE_ID => $fund->vendingMachineId()->value(),
            GetFundQueryResponse::CASH_ITEMS => array_map($this->buildFundItemFn(), $fund->cashItems()->toArray()),
        ]);
    }

    public function buildFundItemFn(): callable
    {
        return fn (CashItem $cashItem) => [
            GetFundQueryResponse::CASH_ITEM_ID => $cashItem->id()->value(),
            GetFundQueryResponse::CURRENCY_ID => $cashItem->currencyId()->value(),
            GetFundQueryResponse::AMOUNT => $cashItem->amount()->value(),
        ];
    }
}
