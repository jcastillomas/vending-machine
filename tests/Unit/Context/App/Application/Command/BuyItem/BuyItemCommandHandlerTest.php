<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\App\Application\Command\BuyItem;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\App\Application\Command\BuyItem\BuyItemCommand;
use VM\App\Application\Command\BuyItem\BuyItemCommandHandler;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponse;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponseConverter;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQuery;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryHandler;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryResponseConverter;
use VM\Context\Payment\Application\Query\GetFund\GetFundQuery;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryHandler;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryResponseConverter;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Entity\CashItems;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Product\Application\Query\GetProduct\GetProductQuery;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryHandler;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponse;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponseConverter;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Shared\Domain\Exception\ConflictException;
use VM\Tests\Infrastructure\Context\Payment\Application\Query\GetCurrenciesQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Payment\Application\Query\GetFundQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\FundIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\CashItemIdStub;
use VM\Tests\Infrastructure\Context\Product\Application\Query\GetProductQueryHandlerMock;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValueStub;

final class BuyItemCommandHandlerTest extends TestCase
{
    private BuyItemCommandHandler $handler;
    private GetProductQueryHandlerMock $getProductQueryHandlerMock;
    private GetCurrenciesQueryHandlerMock $getCurrenciesQueryHandlerMock;
    private GetFundQueryHandlerMock $getFundQueryHandlerMock;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->getProductQueryHandlerMock = new GetProductQueryHandlerMock($prophet->prophesize(GetProductQueryHandler::class));
        $this->getCurrenciesQueryHandlerMock = new GetCurrenciesQueryHandlerMock($prophet->prophesize(GetCurrenciesQueryHandler::class));
        $this->getFundQueryHandlerMock = new GetFundQueryHandlerMock($prophet->prophesize(GetFundQueryHandler::class));
        $this->handler = new BuyItemCommandHandler(
            new BuyItemCommandResponseConverter(),
            $this->getProductQueryHandlerMock->reveal(),
            $this->getCurrenciesQueryHandlerMock->reveal(),
            $this->getFundQueryHandlerMock->reveal()
        );
    }

    public function test_it_should_retrieve_item_and_change(): void
    {
        $command = $this->givenACommand();
        $getProductQuery = $this->givenAGetProductQuery($command);
        $getCurrenciesQuery = $this->givenAGetCurrenciesQuery();
        $getFundQuery = $this->givenAGetFundQuery();
        $product = $this->givenAProduct($command);
        $currencies = $this->givenCurrencies();
        $fund = $this->givenFund($currencies);
        $this->thenGetProductQueryHandlerMockMockShouldBeInvoke($getProductQuery, $product);
        $this->thenGetCurrenciesQueryHandlerMockMockShouldBeInvoke($getCurrenciesQuery, $currencies);
        $this->thenGetFundQueryHandlerMockMockShouldBeInvoke($getFundQuery, $fund);

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

    public function test_it_should_throw_conflict_exception_on_insufficient_fund(): void
    {
        $this->expectException(ConflictException::class);
        $command = $this->givenACommand();
        $getProductQuery = $this->givenAGetProductQuery($command);
        $getCurrenciesQuery = $this->givenAGetCurrenciesQuery();
        $getFundQuery = $this->givenAGetFundQuery();
        $product = $this->givenAProduct($command);
        $currencies = $this->givenCurrencies();
        $fund = $this->givenemptyFund($currencies);
        $this->thenGetProductQueryHandlerMockMockShouldBeInvoke($getProductQuery, $product);
        $this->thenGetCurrenciesQueryHandlerMockMockShouldBeInvoke($getCurrenciesQuery, $currencies);
        $this->thenGetFundQueryHandlerMockMockShouldBeInvoke($getFundQuery, $fund);
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

    private function thenGetProductQueryHandlerMockMockShouldBeInvoke(GetProductQuery $command, Product $product): void
    {
        $converter = new GetProductQueryResponseConverter();
        $this->getProductQueryHandlerMock->shouldInvoke($command, $converter($product));
    }

    private function thenGetCurrenciesQueryHandlerMockMockShouldBeInvoke(GetCurrenciesQuery $command, array $currencies): void
    {
        $converter = new GetCurrenciesQueryResponseConverter();
        $this->getCurrenciesQueryHandlerMock->shouldInvoke($command, $converter($currencies));
    }

    private function thenGetFundQueryHandlerMockMockShouldBeInvoke(GetFundQuery $command, Fund $fund): void
    {
        $converter = new GetFundQueryResponseConverter();
        $this->getFundQueryHandlerMock->shouldInvoke($command, $converter($fund));
    }

    private function whenHandlingCommand(BuyItemCommand $query): BuyItemCommandResponse
    {
        return $this->handler->__invoke($query);
    }
}
