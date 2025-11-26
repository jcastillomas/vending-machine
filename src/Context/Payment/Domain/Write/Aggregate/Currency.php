<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Aggregate;

use DateTimeImmutable;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKind;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Shared\Domain\Write\Aggregate\AggregateRoot;

class Currency extends AggregateRoot
{
    private CurrencyValue $value;
    private CurrencyKind $kind;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        CurrencyId $currencyId,
        CurrencyValue $currencyValue,
        CurrencyKind $currencyKind
    ): self {
        $currency =  new self($currencyId);
        $currency->value = $currencyValue;
        $currency->kind = $currencyKind;
        $currency->createdAt = new DateTimeImmutable();
        $currency->updatedAt = null;
        return $currency;
    }

    public function value(): CurrencyValue
    {
        return $this->value;
    }

    public function kind(): CurrencyKind
    {
        return $this->kind;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
