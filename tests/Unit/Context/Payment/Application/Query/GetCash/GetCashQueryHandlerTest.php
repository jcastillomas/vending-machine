<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Query\GetCash;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Query\GetCash\GetCashQuery;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryHandler;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryResponse;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryResponseConverter;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CashIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\CashRepositoryMock;

final class GetCashQueryHandlerTest extends TestCase
{
    private CashRepositoryMock $cashRepository;
    private GetCashQueryHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->cashRepository = new CashRepositoryMock($prophet->prophesize(CashRepository::class));
        $this->handler = new GetCashQueryHandler($this->cashRepository->reveal(), new GetCashQueryResponseConverter());
    }

    public function test_it_should_retrieve_cash(): void
    {
        $cash = $this->givenACash();
        $query = $this->givenAQuery();
        $this->thenCashShouldBeFound($cash);
        $queryResponse = $this->whenHandlingQuery($query);

        $this->assertIsArray($queryResponse->result());

        $this->assertEquals($cash->id()->value(), $queryResponse->id());
        $this->assertEquals($cash->vendingMachineId()->value(), $queryResponse->vendingMachineId());
        $this->assertCount(count($cash->cashItems()), $queryResponse->cashItems());
    }

    private function givenACash(): Cash
    {
        return Cash::create(
            CashIdStub::random(),
            VendingMachineIdStub::random(),
            CashItemsStub::random()
        );
    }

    private function givenAQuery(): GetCashQuery
    {
        return GetCashQuery::create();
    }

    private function whenHandlingQuery(GetCashQuery $query): GetCashQueryResponse
    {
        return $this->handler->__invoke($query);
    }

    private function thenCashShouldBeFound(Cash $cash): void
    {
        $this->cashRepository->shouldFindVendingMachine($cash);
    }
}
