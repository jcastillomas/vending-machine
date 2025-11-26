<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CashId;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;

interface CashRepository
{
    public function save(Cash $currency): void;
    public function find(CashId $currencyId): Cash;
}
