<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;

interface CurrencyRepository
{
    public function save(Currency $currency): void;
    public function find(CurrencyId $currencyId): Currency;
}
