<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\CreateCurrency;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKind;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Shared\Application\Bus\Command\Command;

class CreateCurrencyCommand extends Command
{
    private const ID = 'id';
    private const VALUE = 'value';
    private const KIND = 'kind';

    public static function create(
        string $id,
        float $value,
        string $kind,
    ): self {
        return new self([
            self::ID => $id,
            self::VALUE => $value,
            self::KIND => $kind,
        ]);
    }

    public function id(): CurrencyId
    {
        return CurrencyId::fromString($this->get(self::ID));
    }

    public function value(): CurrencyValue
    {
        return CurrencyValue::fromFloat($this->get(self::VALUE));
    }

    public function kind(): CurrencyKind
    {
        return CurrencyKind::fromString($this->get(self::KIND));
    }

    public static function messageName(): string
    {
        return 'command.payment.create_currency';
    }
}
