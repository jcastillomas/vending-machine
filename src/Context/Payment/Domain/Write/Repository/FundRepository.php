<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\FundId;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;

interface FundRepository
{
    public function save(Fund $fund): void;
    public function find(FundId $fundId): Fund;
}
