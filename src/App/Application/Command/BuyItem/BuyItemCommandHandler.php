<?php

declare(strict_types=1);

namespace VM\App\Application\Command\BuyItem;

use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQuery;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryHandler;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQueryResponse;
use VM\Context\Payment\Application\Query\GetFund\GetFundQuery;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryHandler;
use VM\Context\Payment\Application\Query\GetFund\GetFundQueryResponse;
use VM\Context\Product\Application\Query\GetProduct\GetProductQuery;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryHandler;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;
use VM\Shared\Domain\Exception\ConflictException;

final readonly class BuyItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BuyItemCommandResponseConverter $responseConverter,
        private GetProductQueryHandler $getProductQueryHandler,
        private GetCurrenciesQueryHandler $getCurrenciesQueryHandler,
        private GetFundQueryHandler $getFundQueryHandler,
    ) {
    }

    /*
        TODO: @Buy item use case:
            5. Calculate change
            6. Find Cash
            7. Calculate change in Cash
            8. Update Stock
            9. Update Cash
           10. Reset Fund
           11. Return Response
     */
    /**
     * Execution process:
     *  1. Find Product by name
     *  2. Find Currencies
     *  3. Find Fund
     *  4. Check fund amount and product value
     *  5. Calculate change
     *  6. Find Cash
     *  7. Calculate change in Cash
     *  8. Update Stock
     *  9. Update Cash
     * 10. Reset Fund
     * 11. Return Response
     * @param BuyItemCommand $command
     * @return BuyItemCommandResponse
     */
    public function __invoke(BuyItemCommand $command): BuyItemCommandResponse
    {
        $product = $this->getProductQueryHandler->__invoke(GetProductQuery::create($command->productName()));
        $currencies = $this->getCurrenciesQueryHandler->__invoke(GetCurrenciesQuery::create());
        $fund = $this->getFundQueryHandler->__invoke(GetFundQuery::create());

        $totalFund = $this->calculateTotalAmountOfFund($currencies, $fund);

        if ($totalFund < $product->productValue()) {
            Throw new ConflictException(sprintf(
                'Insufficient fund. Missing %f to reach the product value.',
                $product->productValue() - $totalFund
            ));
        }

        return $this->responseConverter->__invoke(strtoupper($product->productName()), ["0.25", "0.10"]);
    }

    private function calculateTotalAmountOfFund(GetCurrenciesQueryResponse $currencies, GetFundQueryResponse $fund)
    {
        $totalFund = 0.0;
        foreach($fund->cashItems() as $fundItem) {
            foreach ($currencies->result() as $currency) {
                if ($currency[GetCurrenciesQueryResponse::ID] == $fundItem[GetFundQueryResponse::CURRENCY_ID]) {
                    $totalFund += ($currency[GetCurrenciesQueryResponse::CURRENCY_VALUE] * $fundItem[GetFundQueryResponse::AMOUNT]);
                }
            }
        }

        return $totalFund;
    }
}
