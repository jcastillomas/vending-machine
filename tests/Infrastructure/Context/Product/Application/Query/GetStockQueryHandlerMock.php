<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Application\Query;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Product\Application\Query\GetStock\GetStockQuery;
use VM\Context\Product\Application\Query\GetStock\GetStockQueryHandler;
use VM\Context\Product\Application\Query\GetStock\GetStockQueryResponse;
use VM\Shared\Domain\Service\Assertion\Assert;

class GetStockQueryHandlerMock
{
    private ObjectProphecy|GetStockQueryHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): GetStockQueryHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): GetStockQueryHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(GetStockQuery $expectedQuery, GetStockQueryResponse $getStockQueryResponse)
    {
        $this->mock
            ->__invoke(Argument::that(function (GetStockQuery $actualQuery) use ($expectedQuery) {
                Assert::eq($expectedQuery->payload(), $actualQuery->payload());

                return true;
            }))
            ->shouldBeCalledOnce()
            ->willReturn($getStockQueryResponse);
    }
}
