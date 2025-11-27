<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\AddFund;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Shared\Application\Bus\Command\Command;

class AddFundCommand extends Command
{
    private const CURRENCY_VALUE = 'currency_value';

    public static function create(
        string $currencyValue
    ): self {
        return new self([
            self::CURRENCY_VALUE => $currencyValue
        ]);
    }

    public function currencyValue(): CurrencyValue
    {
        return CurrencyValue::fromString($this->get(self::CURRENCY_VALUE));
    }

    public static function messageName(): string
    {
        return 'command.payment.add_fund';
    }
}
