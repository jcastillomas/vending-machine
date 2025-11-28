<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Application\Command\UpdateStock;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommand;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommandHandler;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Context\Product\Domain\Write\Entity\StockItem;
use VM\Context\Product\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Product\Domain\Write\Repository\ProductRepository;
use VM\Context\Product\Domain\Write\Repository\StockRepository;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValueStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\StockIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\StockItemsStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject\AmountStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject\StockItemIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Repository\ProductRepositoryMock;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Repository\StockRepositoryMock;

class UpdateStockCommandHandlerTest extends TestCase
{
    private ProductRepositoryMock $productRepository;
    private StockRepositoryMock $stockRepository;
    private UpdateStockCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->productRepository = new ProductRepositoryMock($prophet->prophesize(ProductRepository::class));
        $this->stockRepository = new StockRepositoryMock($prophet->prophesize(StockRepository::class));
        $this->handler = new UpdateStockCommandHandler($this->stockRepository->reveal(), $this->productRepository->reveal());
    }

    public function test_it_should_update_stock_successfully()
    {
        $command = $this->givenACommand();
        $product = $this->givenProduct($command);
        $stock = $this->givenStock($product);
        foreach ($command->stock() as $itemStock){
            $this->thenProductShouldBeFoundByProductName($itemStock[UpdateStockCommand::STOCK_NAME], $product);
        }
        $this->thenVandingMachineStockShouldBeFound($stock);
        $this->thenStockShouldBeSaved($stock);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->productRepository->mock()->checkProphecyMethodsPredictions();
        $this->stockRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): UpdateStockCommand
    {
        return UpdateStockCommand::create(
            [
                [
                    UpdateStockCommand::STOCK_NAME => ProductNameStub::random()->value(),
                    UpdateStockCommand::STOCK_AMOUNT => AmountStub::random()->value(),
                ]
            ]
        );
    }

    public function givenProduct(UpdateStockCommand $command): Product
    {
        return Product::create(
            ProductIdStub::random(),
            $command->stock()[0][UpdateStockCommand::STOCK_NAME],
            ProductValueStub::random()
        );
    }

    public function givenStock(Product $product): Stock
    {
        $stock = Stock::create(
            StockIdStub::random(),
            VendingMachineIdStub::random(),
            StockItemsStub::random()
        );

        $stock->stockItems()->add(StockItem::create(
            StockItemIdStub::random(),
            $product->id(),
            Amount::fromInt(0)
        ));

        return $stock;
    }

    private function thenProductShouldBeFoundByProductName(ProductName $productName, Product $product)
    {
        $this->productRepository->shouldFindByName($productName, $product);
    }

    private function thenVandingMachineStockShouldBeFound(Stock $stock)
    {
        $this->stockRepository->shouldFindVendingMachine($stock);
    }

    private function thenStockShouldBeSaved(Stock $stock)
    {
        $this->stockRepository->shouldSave($stock);
    }

    private function whenHandlingCommand(UpdateStockCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
