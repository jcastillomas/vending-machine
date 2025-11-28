<?php

declare(strict_types=1);

namespace VM\Tests\Integration\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValueStub;
use VM\Tests\Infrastructure\Integration\MySQL\AggregateRepositoryTestCase;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineCurrencyRepositoryTest extends AggregateRepositoryTestCase
{
    public function test_it_saves_and_finds_a_currency(): void
    {
        $expectedCurrency = $this->givenACurrency();
        $this->whenACurrencyIsSaved($expectedCurrency);
        $this->thenACurrencyIsFound($expectedCurrency);
    }

    public function test_it_finds_by_value(): void
    {
        $expectedCurrency = $this->givenACurrency();
        $this->whenACurrencyIsSaved($expectedCurrency);
        $this->thenACurrencyIsFoundByCurrencyValue($expectedCurrency);
    }

    public function test_it_finds_all_currencies(): void
    {
        $expectedCurrency = $this->givenACurrency();
        $this->whenACurrencyIsSaved($expectedCurrency);
        $this->thenCurrenciesAreFound([$expectedCurrency]);
    }

    private function givenACurrency(): Currency
    {
        return Currency::create(
            CurrencyIdStub::random(),
            CurrencyValueStub::random(),
            CurrencyKindStub::random()
        );
    }

    private function whenACurrencyIsSaved(Currency $vendingMachine): void
    {
        $this->repository->save($vendingMachine);
        $this->em()->flush();
        $this->em()->clear();
    }

    private function thenACurrencyIsFound(Currency $expectedCurrency): void
    {
        $actualCurrency = $this->repository->find($expectedCurrency->id());
        $this->assertEquals($expectedCurrency->id(), $actualCurrency->id());
        $this->assertEquals($expectedCurrency->createdAt()->getTimestamp(), $actualCurrency->createdAt()->getTimestamp());
        $this->assertEquals($expectedCurrency->updatedAt()?->getTimestamp(), $actualCurrency->updatedAt()?->getTimestamp());
    }

    private function thenACurrencyIsFoundByCurrencyValue(Currency $expectedCurrency): void
    {
        $actualCurrency = $this->repository->findByValue($expectedCurrency->value());
        $this->assertEquals($expectedCurrency->id(), $actualCurrency->id());
        $this->assertEquals($expectedCurrency->createdAt()->getTimestamp(), $actualCurrency->createdAt()->getTimestamp());
        $this->assertEquals($expectedCurrency->updatedAt()?->getTimestamp(), $actualCurrency->updatedAt()?->getTimestamp());
    }

    private function thenCurrenciesAreFound(array $expectedCurrencies): void
    {
        $actualCurrencies = $this->repository->findCurrencies();
        $this->assertEquals(count($expectedCurrencies), count($actualCurrencies));
    }

    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . CurrencyRepository::class);
    }

    protected function purge(): void
    {
        $this->purgeTables( 'currency');
    }
}
