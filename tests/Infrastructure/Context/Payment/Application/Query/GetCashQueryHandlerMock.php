<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Application\Query;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Application\Query\GetCash\GetCashQuery;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryHandler;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryResponse;
use VM\Shared\Domain\Service\Assertion\Assert;

class GetCashQueryHandlerMock
{
    private ObjectProphecy|GetCashQueryHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): GetCashQueryHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): GetCashQueryHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(GetCashQuery $expectedQuery, GetCashQueryResponse $getCashQueryResponse)
    {
        $this->mock
            ->__invoke(Argument::that(function (GetCashQuery $actualQuery) use ($expectedQuery) {
                Assert::eq($expectedQuery->payload(), $actualQuery->payload());

                return true;
            }))
            ->shouldBeCalledOnce()
            ->willReturn($getCashQueryResponse);
    }
}
