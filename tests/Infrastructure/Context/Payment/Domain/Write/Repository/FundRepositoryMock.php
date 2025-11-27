<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Shared\Domain\Service\Assertion\Assert;

class FundRepositoryMock
{
    private ObjectProphecy|FundRepository $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): FundRepository
    {
        return $this->mock->reveal();
    }

    public function mock(): FundRepository|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldSave(Fund $expectedFund)
    {
        $this->mock
            ->save(Argument::that(function (Fund $actualFund) use ($expectedFund) {
                Assert::eq($expectedFund->id(), $actualFund->id());
                Assert::eq($expectedFund->vendingMachineId(), $actualFund->vendingMachineId());
                Assert::eq($expectedFund->cashItems()->count(), $actualFund->cashItems()->count());

                return true;
            }))
            ->shouldBeCalledOnce();
    }

    public function findByCurrencyId(CurrencyId $currencyId, Fund $expectedFund)
    {
        $this->mock
            ->findByCurrencyId($currencyId)
            ->shouldBeCalledOnce()
            ->willReturn($expectedFund);
    }

    public function findVendingMachine(Fund $expectedFund)
    {
        $this->mock
            ->findVendingMachine()
            ->shouldBeCalledOnce()
            ->willReturn($expectedFund);
    }
}
