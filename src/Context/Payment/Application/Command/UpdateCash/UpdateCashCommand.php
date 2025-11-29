<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\UpdateCash;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Shared\Application\Bus\Command\Command;

class UpdateCashCommand extends Command
{
    private const CASH = 'cash';
    public const CASH_VALUE = 'cash_value';
    public const CASH_AMOUNT = 'cash_amount';

    public static function create(
        array $cash
    ): self {
        return new self([
            self::CASH => $cash
        ]);
    }

    public function cash(): array
    {
        return array_map(
            fn (array $cashItem) => [
                self::CASH_VALUE => CurrencyValue::fromString($cashItem[self::CASH_VALUE]),
                self::CASH_AMOUNT => Amount::fromInt($cashItem[self::CASH_AMOUNT]),
            ],
            $this->get(self::CASH)
        );
    }

    public static function messageName(): string
    {
        return 'command.payment.update_cash';
    }
}
