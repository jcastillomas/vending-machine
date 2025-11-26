<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Shared\Domain\Service\Assertion\Assert;

class CashRepositoryMock
{
    private ObjectProphecy|CashRepository $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): CashRepository
    {
        return $this->mock->reveal();
    }

    public function mock(): CashRepository|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldSave(Cash $expectedCash)
    {
        $this->mock
            ->save(Argument::that(function (Cash $actualCash) use ($expectedCash) {
                Assert::eq($expectedCash->id(), $actualCash->id());
                Assert::eq($expectedCash->vendingMachineId(), $actualCash->vendingMachineId());
                Assert::eq($expectedCash->cashItems()->count(), $actualCash->cashItems()->count());

                return true;
            }))
            ->shouldBeCalledOnce();
    }
}
