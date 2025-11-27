<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\ResetFund;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Shared\Application\Bus\Command\Command;

class ResetFundCommand extends Command
{
    public static function create(
    ): self {
        return new self([]);
    }

    public static function messageName(): string
    {
        return 'command.payment.reset_fund';
    }
}
