<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\App\Application\Command\BuyItem;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\App\Application\Command\BuyItem\BuyItemCommand;
use VM\App\Application\Command\BuyItem\BuyItemCommandHandler;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponse;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponseConverter;
use VM\Context\Payment\Application\Command\ResetFund\ResetFundCommand;
use VM\Context\Payment\Application\Command\ResetFund\ResetFundCommandHandler;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommand;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommandHandler;
use VM\Context\Payment\Application\Query\GetCash\GetCashQuery;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryHandler;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryResponseConverter;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQuery;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryHandler;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryResponseConverter;
use VM\Context\Payment\Application\Query\GetFund\GetFundQuery;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryHandler;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryResponseConverter;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Entity\CashItems;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommand;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommandHandler;
use VM\Context\Product\Application\Query\GetProduct\GetProductQuery;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryHandler;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponse;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponseConverter;
use VM\Context\Product\Application\Query\GetStock\GetStockQuery;
use VM\Context\Product\Application\Query\GetStock\GetStockQueryHandler;
use VM\Context\Product\Application\Query\GetStock\GetStockQueryResponseConverter;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Aggregate\Stock;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Context\Product\Domain\Write\Entity\StockItem;
use VM\Context\Product\Domain\Write\Entity\StockItems;
use VM\Shared\Domain\Exception\ConflictException;
use VM\Tests\Infrastructure\Context\Payment\Application\Command\ResetFundCommandHandlerMock;
use VM\Tests\Infrastructure\Context\Payment\Application\Command\UpdateCashCommandHandlerMock;
use VM\Tests\Infrastructure\Context\Payment\Application\Query\GetCashQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Payment\Application\Query\GetCurrenciesQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Payment\Application\Query\GetFundQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CashIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\FundIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\CashItemIdStub;
use VM\Tests\Infrastructure\Context\Product\Application\Command\UpdateStockCommandHandlerMock;
use VM\Tests\Infrastructure\Context\Product\Application\Query\GetProductQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Product\Application\Query\GetStockQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValueStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\StockIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Entity\ValueObject\StockItemIdStub;

final class BuyItemCommandHandlerTest extends TestCase
{
    private BuyItemCommandHandler $handler;
    private GetProductQueryHandlerMock $getProductQueryHandlerMock;
    private GetCurrenciesQueryHandlerMock $getCurrenciesQueryHandlerMock;
    private GetFundQueryHandlerMock $getFundQueryHandlerMock;
    private GetCashQueryHandlerMock $getCashQueryHandlerMock;
    private GetStockQueryHandlerMock $getStockQueryHandlerMock;
    private UpdateStockCommandHandlerMock $updateStockCommandHandlerMock;
    private UpdateCashCommandHandlerMock $updateCashCommandHandlerMock;
    private ResetFundCommandHandlerMock $resetFundCommandHandlerMock;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->getProductQueryHandlerMock = new GetProductQueryHandlerMock($prophet->prophesize(GetProductQueryHandler::class));
        $this->getCurrenciesQueryHandlerMock = new GetCurrenciesQueryHandlerMock($prophet->prophesize(GetCurrenciesQueryHandler::class));
        $this->getFundQueryHandlerMock = new GetFundQueryHandlerMock($prophet->prophesize(GetFundQueryHandler::class));
        $this->getCashQueryHandlerMock = new GetCashQueryHandlerMock($prophet->prophesize(GetCashQueryHandler::class));
        $this->getStockQueryHandlerMock = new GetStockQueryHandlerMock($prophet->prophesize(GetStockQueryHandler::class));
        $this->updateStockCommandHandlerMock = new UpdateStockCommandHandlerMock($prophet->prophesize(UpdateStockCommandHandler::class));
        $this->updateCashCommandHandlerMock = new UpdateCashCommandHandlerMock($prophet->prophesize(UpdateCashCommandHandler::class));
        $this->resetFundCommandHandlerMock = new ResetFundCommandHandlerMock($prophet->prophesize(ResetFundCommandHandler::class));
        $this->handler = new BuyItemCommandHandler(
            new BuyItemCommandResponseConverter(),
            $this->getProductQueryHandlerMock->reveal(),
            $this->getCurrenciesQueryHandlerMock->reveal(),
            $this->getFundQueryHandlerMock->reveal(),
            $this->getCashQueryHandlerMock->reveal(),
            $this->getStockQueryHandlerMock->reveal(),
            $this->updateStockCommandHandlerMock->reveal(),
            $this->updateCashCommandHandlerMock->reveal(),
            $this->resetFundCommandHandlerMock->reveal()
        );
    }

    public function test_it_should_retrieve_item_and_change(): void
    {
        $command = $this->givenACommand();
        $getProductQuery = $this->givenAGetProductQuery($command);
        $getCurrenciesQuery = $this->givenAGetCurrenciesQuery();
        $getFundQuery = $this->givenAGetFundQuery();
        $getStockQuery = $this->givenAGetStockQuery();
        $getCashQuery = $this->givenAGetCashQuery();
        $updateStockCommand = $this->givenAUpdateStockCommand($command);
        $updateCashCommand = $this->givenAUpdateCashCommand();
        $resetFundCommand = $this->givenAResetFundCommand();
        $product = $this->givenAProduct($command);
        $currencies = $this->givenCurrencies();
        $fund = $this->givenFund($currencies);
        $cash = $this->givenCash($currencies);
        $stock = $this->givenStock($product);
        $this->thenGetProductQueryHandlerMockMockShouldBeInvoke($getProductQuery, $product);
        $this->thenGetCurrenciesQueryHandlerMockMockShouldBeInvoke($getCurrenciesQuery, $currencies);
        $this->thenGetFundQueryHandlerMockMockShouldBeInvoke($getFundQuery, $fund);
        $this->thenGetCashQueryHandlerMockMockShouldBeInvoke($getCashQuery, $cash);
        $this->thenGetStockQueryHandlerMockMockShouldBeInvoke($getStockQuery, $stock);
        $this->thenUpdateStockCommandHandlerMockMockShouldBeInvoke($updateStockCommand);
        $this->thenUpdateCashCommandHandlerMockMockShouldBeInvoke($updateCashCommand);
        $this->thenResetFundCommandHandlerMockMockShouldBeInvoke($resetFundCommand);

        $queryResponse = $this->whenHandlingCommand($command);

        $this->assertIsArray($queryResponse->result());

        foreach ($queryResponse->result() as $index => $value) {
            if ($index == 0) {
                $this->assertIsString($value);
            } else {
                $this->assertIsFloat($value);
            }
        }
    }

    public function test_it_should_throw_conflict_exception_on_insufficient_fund(): void
    {
        $this->expectException(ConflictException::class);
        $command = $this->givenACommand();
        $getProductQuery = $this->givenAGetProductQuery($command);
        $getCurrenciesQuery = $this->givenAGetCurrenciesQuery();
        $getFundQuery = $this->givenAGetFundQuery();
        $getStockQuery = $this->givenAGetStockQuery();
        $product = $this->givenAProduct($command);
        $currencies = $this->givenCurrencies();
        $fund = $this->givenemptyFund($currencies);
        $stock = $this->givenStock($product);
        $this->thenGetProductQueryHandlerMockMockShouldBeInvoke($getProductQuery, $product);
        $this->thenGetCurrenciesQueryHandlerMockMockShouldBeInvoke($getCurrenciesQuery, $currencies);
        $this->thenGetFundQueryHandlerMockMockShouldBeInvoke($getFundQuery, $fund);
        $this->thenGetStockQueryHandlerMockMockShouldBeInvoke($getStockQuery, $stock);
        $this->whenHandlingCommand($command);
    }

    private function givenACommand(): BuyItemCommand
    {
        return BuyItemCommand::create(ProductNameStub::random()->value());
    }

    private function givenAGetProductQuery(BuyItemCommand $command): GetProductQuery
    {
        return GetProductQuery::create($command->productName());
    }

    private function givenAGetCurrenciesQuery(): GetCurrenciesQuery
    {
        return GetCurrenciesQuery::create();
    }

    private function givenAGetFundQuery(): GetFundQuery
    {
        return GetFundQuery::create();
    }

    private function givenAGetStockQuery(): GetStockQuery
    {
        return GetStockQuery::create();
    }

    private function givenAGetCashQuery(): GetCashQuery
    {
        return GetCashQuery::create();
    }

    private function givenAUpdateStockCommand(BuyItemCommand $command): UpdateStockCommand
    {
        return UpdateStockCommand::create([
            [
                UpdateStockCommand::STOCK_NAME => $command->productName(),
                UpdateStockCommand::STOCK_AMOUNT => 2,
            ]
        ]);
    }

    private function givenAUpdateCashCommand(): UpdateCashCommand
    {
        return UpdateCashCommand::create([
            [
                UpdateCashCommand::CASH_VALUE => 0.10,
                UpdateCashCommand::CASH_AMOUNT => 6,
            ]
        ]);
    }

    private function givenAResetFundCommand(): ResetFundCommand
    {
        return ResetFundCommand::create();
    }

    private function givenAProduct(BuyItemCommand $command): Product
    {
        return Product::create(
            ProductIdStub::random(),
            ProductName::fromString($command->productName()),
            ProductValueStub::create(1.10)
        );
    }

    private function givenCurrencies(): array
    {
        return [
            Currency::create(
                CurrencyIdStub::random(),
                CurrencyValue::fromFloat(0.50),
                CurrencyKindStub::create("EUR")
            ),
            Currency::create(
                CurrencyIdStub::random(),
                CurrencyValue::fromFloat(0.10),
                CurrencyKindStub::create("EUR")
            )
        ];
    }

    private function givenFund(array $currencies): Fund
    {
        return Fund::create(
            FundIdStub::random(),
            VendingMachineIdStub::random(),
            CashItems::create([
                CashItem::create(
                    CashItemIdStub::random(),
                    $currencies[0]->id(),
                    Amount::fromInt(3)
                ),
                CashItem::create(
                    CashItemIdStub::random(),
                    $currencies[1]->id(),
                    Amount::fromInt(0)
                ),
            ])
        );
    }

    private function givenCash(array $currencies): Cash
    {
        return Cash::create(
            CashIdStub::random(),
            VendingMachineIdStub::random(),
            CashItems::create([
                CashItem::create(
                    CashItemIdStub::random(),
                    $currencies[0]->id(),
                    Amount::fromInt(0)
                ),
                CashItem::create(
                    CashItemIdStub::random(),
                    $currencies[1]->id(),
                    Amount::fromInt(10)
                ),
            ])
        );
    }

    private function givenStock(Product $product): Stock
    {
        return Stock::create(
            StockIdStub::random(),
            \VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub::random(),
            StockItems::create([
                StockItem::create(
                    StockItemIdStub::random(),
                    $product->id(),
                    \VM\Context\Product\Domain\Write\Entity\ValueObject\Amount::fromInt(10)
                ),
                StockItem::create(
                    StockItemIdStub::random(),
                    ProductIdStub::random(),
                    \VM\Context\Product\Domain\Write\Entity\ValueObject\Amount::fromInt(10)
                ),
            ])
        );
    }

    private function givenEmptyFund(array $currencies): Fund
    {
        return Fund::create(
            FundIdStub::random(),
            VendingMachineIdStub::random(),
            CashItems::create([
                CashItem::create(
                    CashItemIdStub::random(),
                    $currencies[0]->id(),
                    Amount::fromInt(0)
                ),
                CashItem::create(
                    CashItemIdStub::random(),
                    $currencies[1]->id(),
                    Amount::fromInt(0)
                ),
            ])
        );
    }

    private function thenGetProductQueryHandlerMockMockShouldBeInvoke(GetProductQuery $query, Product $product): void
    {
        $converter = new GetProductQueryResponseConverter();
        $this->getProductQueryHandlerMock->shouldInvoke($query, $converter($product));
    }

    private function thenGetCurrenciesQueryHandlerMockMockShouldBeInvoke(GetCurrenciesQuery $query, array $currencies): void
    {
        $converter = new GetCurrenciesQueryResponseConverter();
        $this->getCurrenciesQueryHandlerMock->shouldInvoke($query, $converter($currencies));
    }

    private function thenGetFundQueryHandlerMockMockShouldBeInvoke(GetFundQuery $query, Fund $fund): void
    {
        $converter = new GetFundQueryResponseConverter();
        $this->getFundQueryHandlerMock->shouldInvoke($query, $converter($fund));
    }

    private function thenGetCashQueryHandlerMockMockShouldBeInvoke(GetCashQuery $query, Cash $cash): void
    {
        $converter = new GetCashQueryResponseConverter();
        $this->getCashQueryHandlerMock->shouldInvoke($query, $converter($cash));
    }

    private function thenGetStockQueryHandlerMockMockShouldBeInvoke(GetStockQuery $query, Stock $stock): void
    {
        $converter = new GetStockQueryResponseConverter();
        $this->getStockQueryHandlerMock->shouldInvoke($query, $converter($stock));
    }

    private function thenUpdateStockCommandHandlerMockMockShouldBeInvoke(UpdateStockCommand $command): void
    {
        $this->updateStockCommandHandlerMock->shouldInvoke($command);
    }

    private function thenUpdateCashCommandHandlerMockMockShouldBeInvoke(UpdateCashCommand $command): void
    {
        $this->updateCashCommandHandlerMock->shouldInvoke($command);
    }

    private function thenResetFundCommandHandlerMockMockShouldBeInvoke(ResetFundCommand $command): void
    {
        $this->resetFundCommandHandlerMock->shouldInvoke($command);
    }

    private function whenHandlingCommand(BuyItemCommand $query): BuyItemCommandResponse
    {
        return $this->handler->__invoke($query);
    }
}
