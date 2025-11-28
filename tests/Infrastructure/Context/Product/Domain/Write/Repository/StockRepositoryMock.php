<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Repository;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductId;
use VM\Context\Product\Domain\Write\Repository\StockRepository;
use VM\Shared\Domain\Service\Assertion\Assert;

class StockRepositoryMock
{
    private ObjectProphecy|StockRepository $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): StockRepository
    {
        return $this->mock->reveal();
    }

    public function mock(): StockRepository|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldSave(Stock $expectedStock)
    {
        $this->mock
            ->save(Argument::that(function (Stock $actualStock) use ($expectedStock) {
                Assert::eq($expectedStock->id(), $actualStock->id());
                Assert::eq($expectedStock->vendingMachineId(), $actualStock->vendingMachineId());
                Assert::eq($expectedStock->stockItems()->count(), $actualStock->stockItems()->count());

                return true;
            }))
            ->shouldBeCalledOnce();
    }

    public function shouldFindVendingMachine(Stock $expectedStock)
    {
        $this->mock
            ->findVendingMachine()
            ->shouldBeCalledOnce()
            ->willReturn($expectedStock);
    }
}
