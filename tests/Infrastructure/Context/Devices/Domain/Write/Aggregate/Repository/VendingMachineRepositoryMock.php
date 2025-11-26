<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Devices\Domain\Write\Aggregate\Repository;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;
use VM\Context\Devices\Domain\Write\Repository\VendingMachineRepository;
use VM\Shared\Domain\Service\Assertion\Assert;

class VendingMachineRepositoryMock
{
    private ObjectProphecy|VendingMachineRepository $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): VendingMachineRepository
    {
        return $this->mock->reveal();
    }

    public function mock(): VendingMachineRepository|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldSave(VendingMachine $expectedVendingMachine)
    {
        $this->mock
            ->save(Argument::that(function (VendingMachine $actualVendingMachine) use ($expectedVendingMachine) {
                Assert::eq($expectedVendingMachine->id(), $actualVendingMachine->id());

                return true;
            }))
            ->shouldBeCalledOnce();
    }
}
