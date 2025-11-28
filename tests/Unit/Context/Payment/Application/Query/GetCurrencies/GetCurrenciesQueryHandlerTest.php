<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Query\GetCurrencies;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQuery;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryHandler;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryResponse;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryResponseConverter;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValueStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\CurrencyRepositoryMock;

final class GetCurrenciesQueryHandlerTest extends TestCase
{
    private CurrencyRepositoryMock $currenciesRepository;
    private GetCurrenciesQueryHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->currenciesRepository = new CurrencyRepositoryMock($prophet->prophesize(CurrencyRepository::class));
        $this->handler = new GetCurrenciesQueryHandler($this->currenciesRepository->reveal(), new GetCurrenciesQueryResponseConverter());
    }

    public function test_it_should_retrieve_currencies(): void
    {
        $currencies = $this->givenCurrencies();
        $query = $this->givenAQuery();
        $this->thenCurrencyShouldBeFound($currencies);
        $queryResponse = $this->whenHandlingQuery($query);

        $this->assertIsArray($queryResponse->result());

        $this->assertEquals(count($currencies), count($queryResponse->result()));
        $this->assertArrayHasKey('id', $queryResponse->result()[0]);
        $this->assertArrayHasKey('currency_value', $queryResponse->result()[0]);
        $this->assertArrayHasKey('currency_kind', $queryResponse->result()[0]);
    }

    private function givenCurrencies(): array
    {
        return [
            Currency::create(
                CurrencyIdStub::random(),
                CurrencyValueStub::random(),
                CurrencyKindStub::random()
            ),
            Currency::create(
                CurrencyIdStub::random(),
                CurrencyValueStub::random(),
                CurrencyKindStub::random()
            )
        ];
    }

    private function givenAQuery(): GetCurrenciesQuery
    {
        return GetCurrenciesQuery::create();
    }

    private function whenHandlingQuery(GetCurrenciesQuery $query): GetCurrenciesQueryResponse
    {
        return $this->handler->__invoke($query);
    }

    private function thenCurrencyShouldBeFound(array $currencies): void
    {
        $this->currenciesRepository->shouldFindCurrencies($currencies);
    }
}
