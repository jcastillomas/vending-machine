<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Entity;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\CashItemId;
use VM\Shared\Domain\Write\Aggregate\Entity;

class CashItem extends Entity
{
    private CurrencyId $currencyId;
    private Amount $amount;
    private \DateTimeImmutable $createdAt;
    private ?\DateTimeImmutable $updatedAt;

    public static function create(
        CashItemId $id,
        CurrencyId $currencyId,
        Amount $amount,
    ): self {
        $cashItem = new self($id);
        $cashItem->currencyId = $currencyId;
        $cashItem->amount = $amount;
        $cashItem->createdAt = new \DateTimeImmutable();
        $cashItem->updatedAt = null;
        return $cashItem;
    }

    public function addAmount(): void
    {
        $this->amount = Amount::fromInt($this->amount->value() + 1);
    }

    public function currencyId(): CurrencyId
    {
        return $this->currencyId;
    }

    public function amount(): Amount
    {
        return $this->amount;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
