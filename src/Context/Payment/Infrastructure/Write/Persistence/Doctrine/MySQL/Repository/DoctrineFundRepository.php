<?php

declare(strict_types=1);

namespace VM\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
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

    public function findByCurrencyId(CurrencyId $id): Fund
    {
        $qb = $this->getRepository()->createQueryBuilder('Fund');
        $qb->innerJoin('Fund.cashItems', 'CashItem');
        $qb->andWhere("CashItem.currencyId = :id")
            ->setParameter('id', $id->value());
        $qb->select('Fund');
        return $qb->getQuery()->getResult()[0];
    }

    public function findVendingMachine(): Fund
    {
        return $this->doSearchByCriteria([])[0];
    }

    protected function entityClassName(): string
    {
        return Fund::class;
    }
}
