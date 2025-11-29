<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Payment\Application\Query;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQuery;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryHandler;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryResponse;
use VM\Shared\Domain\Service\Assertion\Assert;

class GetCurrenciesQueryHandlerMock
{
    private ObjectProphecy|GetCurrenciesQueryHandler $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): GetCurrenciesQueryHandler
    {
        return $this->mock->reveal();
    }

    public function mock(): GetCurrenciesQueryHandler|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldInvoke(GetCurrenciesQuery $expectedQuery, GetCurrenciesQueryResponse $getCurrenciesQueryResponse)
    {
        $this->mock
            ->__invoke(Argument::that(function (GetCurrenciesQuery $actualQuery) use ($expectedQuery) {
                Assert::eq($expectedQuery->payload(), $actualQuery->payload());

                return true;
            }))
            ->shouldBeCalledOnce()
            ->willReturn($getCurrenciesQueryResponse);
    }
}
