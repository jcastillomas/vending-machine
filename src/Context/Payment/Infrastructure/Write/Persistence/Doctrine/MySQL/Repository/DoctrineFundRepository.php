<?php

declare(strict_types=1);

namespace VM\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\FundId;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

class DoctrineFundRepository extends AggregateRepository implements FundRepository
{
    public function save(Fund $fund): void
    {
        $this->saveAggregate($fund);
    }

    public function find(FundId $fundId): Fund
    {
        return $this->doFind($fundId);
    }

    protected function entityClassName(): string
    {
        return Fund::class;
    }
}
