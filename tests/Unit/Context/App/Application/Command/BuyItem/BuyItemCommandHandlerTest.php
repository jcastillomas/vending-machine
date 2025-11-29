<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\App\Application\Command\BuyItem;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\App\Application\Command\BuyItem\BuyItemCommand;
use VM\App\Application\Command\BuyItem\BuyItemCommandHandler;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponse;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponseConverter;
use VM\Context\Product\Application\Query\GetProduct\GetProductQuery;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryHandler;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponse;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponseConverter;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Tests\Infrastructure\Context\Product\Application\Query\GetProductQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValueStub;

final class BuyItemCommandHandlerTest extends TestCase
{
    private BuyItemCommandHandler $handler;
    private GetProductQueryHandlerMock $getProductQueryHandlerMock;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->getProductQueryHandlerMock = new GetProductQueryHandlerMock($prophet->prophesize(GetProductQueryHandler::class));
        $this->handler = new BuyItemCommandHandler(new BuyItemCommandResponseConverter(), $this->getProductQueryHandlerMock->reveal());
    }

    public function test_it_should_retrieve_item_and_change(): void
    {
        $command = $this->givenACommand();
        $getProductQuery = $this->givenAGetProductQuery($command);
        $product = $this->givenAProduct($command);
        $this->thenGetProductQueryHandlerMockMockShouldBeInvoke($getProductQuery, $product);

        $queryResponse = $this->whenHandlingCommand($command);
        $floatRegex = '/^[0-9\,\.]+$/i';

        $this->assertIsArray($queryResponse->result());

        foreach ($queryResponse->result() as $index => $value) {
            if ($index == 0) {
                $this->assertIsString($value);
            } else {
                $this->assertIsString($value);
                $this->assertIsFloat(floatval($value));
                $this->assertMatchesRegularExpression($floatRegex, $value);
            }
        }
    }

    private function givenACommand(): BuyItemCommand
    {
        return BuyItemCommand::create(ProductNameStub::random()->value());
    }

    private function givenAGetProductQuery(BuyItemCommand $command): GetProductQuery
    {
        return GetProductQuery::create($command->productName());
    }

    private function givenAProduct(BuyItemCommand $command): Product
    {
        return Product::create(
            ProductIdStub::random(),
            ProductName::fromString($command->productName()),
            ProductValueStub::random()
        );
    }

    private function thenGetProductQueryHandlerMockMockShouldBeInvoke(GetProductQuery $command, Product $product): void
    {
        $converter = new GetProductQueryResponseConverter();
        $this->getProductQueryHandlerMock->shouldInvoke($command, $converter($product));
    }

    private function whenHandlingCommand(BuyItemCommand $query): BuyItemCommandResponse
    {
        return $this->handler->__invoke($query);
    }
}
