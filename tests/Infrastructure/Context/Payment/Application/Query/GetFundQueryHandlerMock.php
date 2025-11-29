<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Application\Query;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Application\Query\GetFund\GetFundQuery;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryHandler;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryResponse;
use VM\Shared\Domain\Service\Assertion\Assert;

class GetFundQueryHandlerMock
{
    private ObjectProphecy|GetFundQueryHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): GetFundQueryHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): GetFundQueryHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(GetFundQuery $expectedQuery, GetFundQueryResponse $getFundQueryResponse)
    {
        $this->mock
            ->__invoke(Argument::that(function (GetFundQuery $actualQuery) use ($expectedQuery) {
                Assert::eq($expectedQuery->payload(), $actualQuery->payload());

                return true;
            }))
            ->shouldBeCalledOnce()
            ->willReturn($getFundQueryResponse);
    }
}
