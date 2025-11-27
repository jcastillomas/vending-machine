<?php

declare(strict_types=1);

namespace VM\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

class DoctrineCurrencyRepository extends AggregateRepository implements CurrencyRepository
{
    public function save(Currency $currency): void
    {
        $this->saveAggregate($currency);
    }

    public function find(CurrencyId $currencyId): Currency
    {
        return $this->doSearch($currencyId);
    }

    public function findByValue(CurrencyValue $currencyValue): Currency
    {
        return $this->doSearchByCriteria(['value' => $currencyValue])[0];
    }

    protected function entityClassName(): string
    {
        return Currency::class;
    }
}
