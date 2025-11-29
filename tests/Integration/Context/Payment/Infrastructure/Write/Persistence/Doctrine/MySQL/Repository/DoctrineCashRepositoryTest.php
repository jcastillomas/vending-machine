<?php

declare(strict_types=1);

namespace VM\Tests\Integration\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CashIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Integration\MySQL\AggregateRepositoryTestCase;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineCashRepositoryTest extends AggregateRepositoryTestCase
{
    public function test_it_saves_and_finds_cash(): void
    {
        $expectedCash = $this->givenACash();
        $this->whenACashIsSaved($expectedCash);
        $this->thenACashIsFound($expectedCash);
    }

    public function test_it_finds_cash_vending_machine(): void
    {
        $expectedCash = $this->givenACash();
        $this->whenACashIsSaved($expectedCash);
        $this->thenVendingMachineCashIsFound($expectedCash);
    }

    private function givenACash(): Cash
    {
        return Cash::create(
            CashIdStub::random(),
            VendingMachineIdStub::random(),
            CashItemsStub::random()
        );
    }

    private function thenVendingMachineCashIsFound(Cash $expectedCash): void
    {
        $actualCash = $this->repository->findVendingMachine();
        $this->assertEquals($expectedCash->id(), $actualCash->id());
        $this->assertEquals($expectedCash->vendingMachineId(), $actualCash->vendingMachineId());
        $this->assertEquals($expectedCash->cashItems()->count(), $actualCash->cashItems()->count());
        $this->assertEquals($expectedCash->createdAt()->getTimestamp(), $actualCash->createdAt()->getTimestamp());
        $this->assertEquals($expectedCash->updatedAt()?->getTimestamp(), $actualCash->updatedAt()?->getTimestamp());
    }

    private function whenACashIsSaved(Cash $cash): void
    {
        $this->repository->save($cash);
        $this->em()->flush();
        $this->em()->clear();
    }

    private function thenACashIsFound(Cash $expectedCash): void
    {
        $actualCash = $this->repository->find($expectedCash->id());
        $this->assertEquals($expectedCash->id(), $actualCash->id());
        $this->assertEquals($expectedCash->vendingMachineId(), $actualCash->vendingMachineId());
        $this->assertEquals($expectedCash->cashItems()->count(), $actualCash->cashItems()->count());
        $this->assertEquals($expectedCash->createdAt()->getTimestamp(), $actualCash->createdAt()->getTimestamp());
        $this->assertEquals($expectedCash->updatedAt()?->getTimestamp(), $actualCash->updatedAt()?->getTimestamp());
    }

    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . CashRepository::class);
    }

    protected function purge(): void
    {
        $this->purgeTables( 'cash', 'cash_cash_item', 'cash_item');
    }
}
