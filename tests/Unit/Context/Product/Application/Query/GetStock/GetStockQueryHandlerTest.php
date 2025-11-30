<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Application\Query\GetStock;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Product\Application\Query\GetStock\GetStockQuery;
use VM\Context\Product\Application\Query\GetStock\GetStockQueryHandler;
use VM\Context\Product\Application\Query\GetStock\GetStockQueryResponse;
use VM\Context\Product\Application\Query\GetStock\GetStockQueryResponseConverter;
use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Repository\StockRepository;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\StockIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\StockItemsStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Repository\StockRepositoryMock;

class GetStockQueryHandlerTest extends TestCase
{
    private StockRepositoryMock $stockRepository;
    private GetStockQueryHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->stockRepository = new StockRepositoryMock($prophet->prophesize(StockRepository::class));
        $this->handler = new GetStockQueryHandler($this->stockRepository->reveal(), new GetStockQueryResponseConverter());
    }

    public function test_it_should_retrieve_stock(): void
    {
        $stock = $this->givenAStock();
        $query = $this->givenAQuery();
        $this->thenStockShouldBeFound($stock);
        $queryResponse = $this->whenHandlingQuery($query);

        $this->assertIsArray($queryResponse->result());

        $this->assertEquals($stock->id()->value(), $queryResponse->id());
        $this->assertEquals($stock->vendingMachineId()->value(), $queryResponse->vendingMachineId());
        $this->assertCount(count($stock->stockItems()), $queryResponse->stockItems());
    }

    private function givenAStock(): Stock
    {
        return Stock::create(
            StockIdStub::random(),
            VendingMachineIdStub::random(),
            StockItemsStub::random()
        );
    }

    private function givenAQuery(): GetStockQuery
    {
        return GetStockQuery::create();
    }

    private function whenHandlingQuery(GetStockQuery $query): GetStockQueryResponse
    {
        return $this->handler->__invoke($query);
    }

    private function thenStockShouldBeFound(Stock $stock): void
    {
        $this->stockRepository->shouldFindVendingMachine($stock);
    }
}
