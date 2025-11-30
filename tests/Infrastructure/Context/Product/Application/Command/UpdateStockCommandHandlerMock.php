<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Application\Command;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommand;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommandHandler;
use VM\Shared\Domain\Service\Assertion\Assert;

class UpdateStockCommandHandlerMock
{
    private ObjectProphecy|UpdateStockCommandHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): UpdateStockCommandHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): UpdateStockCommandHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(UpdateStockCommand $expectedCommand)
    {
        $this->mock
            ->__invoke(Argument::that(function (UpdateStockCommand $actualCommand) use ($expectedCommand) {
                Assert::eq(count($expectedCommand->stock()), count($actualCommand->stock()));

                return true;
            }))
            ->shouldBeCalledOnce();
    }
}
