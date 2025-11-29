<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Application\Query;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Product\Application\Query\GetProduct\GetProductQuery;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryHandler;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponse;
use VM\Shared\Domain\Service\Assertion\Assert;

class GetProductQueryHandlerMock
{
    private ObjectProphecy|GetProductQueryHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): GetProductQueryHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): GetProductQueryHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(GetProductQuery $expectedQuery, GetProductQueryResponse $getProductQueryResponse)
    {
        $this->mock
            ->__invoke(Argument::that(function (GetProductQuery $actualQuery) use ($expectedQuery) {
                Assert::eq($expectedQuery->productName(), $actualQuery->productName());

                return true;
            }))
            ->shouldBeCalledOnce()
            ->willReturn($getProductQueryResponse);
    }
}
