<?php

declare(strict_types=1);

namespace VM\Tests\Integration\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\FundIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Integration\MySQL\AggregateRepositoryTestCase;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineFundRepositoryTest extends AggregateRepositoryTestCase
{
    public function test_it_saves_and_finds_fund(): void
    {
        $expectedFund = $this->givenAFundWith();
        $this->whenAFundIsSaved($expectedFund);
        $this->thenAFundIsFound($expectedFund);
    }

    public function test_it_finds_fund_by_currency_id(): void
    {
        $expectedFund = $this->givenAFundWith();
        $this->whenAFundIsSaved($expectedFund);
        $this->thenAFundIsFoundByCurrencyId($expectedFund);
    }

    private function givenAFundWith(): Fund
    {
        return Fund::create(
            FundIdStub::random(),
            VendingMachineIdStub::random(),
            CashItemsStub::random()
        );
    }

    private function whenAFundIsSaved(Fund $fund): void
    {
        $this->repository->save($fund);
        $this->em()->flush();
        $this->em()->clear();
    }

    private function thenAFundIsFound(Fund $expectedFund): void
    {
        $actualFund = $this->repository->find($expectedFund->id());
        $this->assertEquals($expectedFund->id(), $actualFund->id());
        $this->assertEquals($expectedFund->vendingMachineId(), $actualFund->vendingMachineId());
        $this->assertEquals($expectedFund->cashItems()->count(), $actualFund->cashItems()->count());
        $this->assertEquals($expectedFund->createdAt()->getTimestamp(), $actualFund->createdAt()->getTimestamp());
        $this->assertEquals($expectedFund->updatedAt()?->getTimestamp(), $actualFund->updatedAt()?->getTimestamp());
    }

    private function thenAFundIsFoundByCurrencyId(Fund $expectedFund): void
    {
        $actualFund = $this->repository->findByCurrencyId($expectedFund->cashItems()->first()->currencyId());
        $this->assertEquals($expectedFund->id(), $actualFund->id());
        $this->assertEquals($expectedFund->vendingMachineId(), $actualFund->vendingMachineId());
        $this->assertEquals($expectedFund->cashItems()->count(), $actualFund->cashItems()->count());
        $this->assertEquals($expectedFund->createdAt()->getTimestamp(), $actualFund->createdAt()->getTimestamp());
        $this->assertEquals($expectedFund->updatedAt()?->getTimestamp(), $actualFund->updatedAt()?->getTimestamp());
    }


    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . FundRepository::class);
    }

    protected function purge(): void
    {
        $this->purgeTables( 'fund', 'fund_cash_item', 'cash_item');
    }
}
