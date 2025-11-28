<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Shared\Domain\Service\Assertion\Assert;

class CurrencyRepositoryMock
{
    private ObjectProphecy|CurrencyRepository $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): CurrencyRepository
    {
        return $this->mock->reveal();
    }

    public function mock(): CurrencyRepository|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldSave(Currency $expectedCurrency)
    {
        $this->mock
            ->save(Argument::that(function (Currency $actualCurrency) use ($expectedCurrency) {
                Assert::eq($expectedCurrency->id(), $actualCurrency->id());
                Assert::eq($expectedCurrency->value(), $actualCurrency->value());
                Assert::eq($expectedCurrency->kind(), $actualCurrency->kind());

                return true;
            }))
            ->shouldBeCalledOnce();
    }

    public function shouldFindByValue(CurrencyValue $CurrencyValue, Currency $expectedCurrency)
    {
        $this->mock
            ->findByValue($CurrencyValue)
            ->shouldBeCalledOnce()
            ->willReturn($expectedCurrency);
    }

    public function shouldFindCurrencies(array $expectedCurrencies)
    {
        $this->mock
            ->findCurrencies()
            ->shouldBeCalledOnce()
            ->willReturn($expectedCurrencies);
    }
}
