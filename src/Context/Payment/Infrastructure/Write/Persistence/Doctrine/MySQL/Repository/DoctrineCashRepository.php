<?php

declare(strict_types=1);

namespace VM\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CashId;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

class DoctrineCashRepository extends AggregateRepository implements CashRepository
{
    public function save(Cash $currency): void
    {
        $this->saveAggregate($currency);
    }

    public function find(CashId $currencyId): Cash
    {
        return $this->doFind($currencyId);
    }

    protected function entityClassName(): string
    {
        return Cash::class;
    }
}
