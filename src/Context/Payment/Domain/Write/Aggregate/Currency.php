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
    private CurrencyValue $currencyValue;
    private CurrencyKind $currencyKind;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $updatedAt;

    public static function create(
        CurrencyId $currencyId,
        CurrencyValue $currencyValue,
        CurrencyKind $currencyKind
    ): self {
        $currency =  new self($currencyId);
        $currency->currencyValue = $currencyValue;
        $currency->currencyKind = $currencyKind;
        $currency->createdAt = new DateTimeImmutable();
        $currency->updatedAt = null;
        return $currency;
    }

    public function currencyValue(): CurrencyValue
    {
        return $this->currencyValue;
    }

    public function currencyKind(): CurrencyKind
    {
        return $this->currencyKind;
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
