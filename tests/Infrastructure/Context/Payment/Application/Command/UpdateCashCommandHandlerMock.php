<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Application\Command;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommand;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommandHandler;
use VM\Shared\Domain\Service\Assertion\Assert;

class UpdateCashCommandHandlerMock
{
    private ObjectProphecy|UpdateCashCommandHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): UpdateCashCommandHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): UpdateCashCommandHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(UpdateCashCommand $expectedCommand)
    {
        $this->mock
            ->__invoke(Argument::that(function (UpdateCashCommand $actualCommand) use ($expectedCommand) {
                Assert::eq($expectedCommand->cash(), $actualCommand->cash());

                return true;
            }))
            ->shouldBeCalledOnce();
    }
}
