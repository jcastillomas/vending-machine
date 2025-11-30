<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Application\Command;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Application\Command\ResetFund\ResetFundCommand;
use VM\Context\Payment\Application\Command\ResetFund\ResetFundCommandHandler;
use VM\Shared\Domain\Service\Assertion\Assert;

class ResetFundCommandHandlerMock
{
    private ObjectProphecy|ResetFundCommandHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): ResetFundCommandHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): ResetFundCommandHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(ResetFundCommand $expectedCommand)
    {
        $this->mock
            ->__invoke(Argument::that(function (ResetFundCommand $actualCommand) use ($expectedCommand) {
                Assert::eq($expectedCommand->payload(), $actualCommand->payload());

                return true;
            }))
            ->shouldBeCalledOnce();
    }
}
