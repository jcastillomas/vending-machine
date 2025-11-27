<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetFund;

use VM\Shared\Application\Bus\Query\Response;

final readonly class GetFundQueryResponse implements Response
{
    public const ID = 'id';
    public const VENDING_MACHINE_ID = 'vending_machine_id';
    public const CASH_ITEMS = 'cash_items';
    public const CASH_ITEM_ID = 'cash_item_id';
    public const CURRENCY_ID = 'currency_id';
    public const AMOUNT = 'amount';

    public function __construct(private array $result)
    {
    }

    public function result(): array
    {
        return $this->result;
    }

    public function id(): string
    {
        return $this->result[self::ID];
    }

    public function vendingMachineId(): string
    {
        return $this->result[self::VENDING_MACHINE_ID];
    }

    public function cashItems(): array
    {
        return $this->result[self::CASH_ITEMS];
    }
}
