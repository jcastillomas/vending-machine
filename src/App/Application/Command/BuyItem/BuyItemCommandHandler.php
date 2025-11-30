<?php

declare(strict_types=1);

namespace VM\App\Application\Command\BuyItem;

use VM\Context\Payment\Application\Query\GetCash\GetCashQuery;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryHandler;
use VM\Context\Payment\Application\Query\GetCash\GetCashQueryResponse;
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
        private GetProductQueryHandler          $getProductQueryHandler,
        private GetCurrenciesQueryHandler       $getCurrenciesQueryHandler,
        private GetFundQueryHandler             $getFundQueryHandler,
        private GetCashQueryHandler             $getCashQueryHandler,
    )
    {
    }

    /*
        TODO: @Buy item use case:
            8. Find Stock
            9. Update Stock
           10. Update Cash
           11. Reset Fund
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
     *  8. Find Stock
     *  9. Update Stock
     * 10. Update Cash
     * 11. Reset Fund
     * 12. Return Response
     * @param BuyItemCommand $command
     * @return BuyItemCommandResponse
     */
    public function __invoke(BuyItemCommand $command): BuyItemCommandResponse
    {
        $change = [];
        $product = $this->getProductQueryHandler->__invoke(GetProductQuery::create($command->productName()));
        $currencies = $this->getCurrenciesQueryHandler->__invoke(GetCurrenciesQuery::create());
        $fund = $this->getFundQueryHandler->__invoke(GetFundQuery::create());

        $totalFund = $this->calculateTotalAmountOfFund($currencies->result(), $fund);

        if ($totalFund < $product->productValue()) {
            throw new ConflictException(sprintf(
                'Insufficient fund. Missing %f to reach the product value.',
                $product->productValue() - $totalFund
            ));
        }

        $totalChange = $totalFund - $product->productValue();

        if ($totalChange > 0.0) {
            $cash = $this->getCashQueryHandler->__invoke(GetCashQuery::create());
            $returnCashAmount = $this->calculateChangeDependingOnCash($totalChange, $cash, $currencies->result());
        }

        foreach ($returnCashAmount as $cashItem) {
            if ($cashItem['amount'] == 0) {
                continue;
            }
            foreach (range(1, $cashItem['amount']) as $count) {
                $change[] = $cashItem['value'];
            }
        }
        return $this->responseConverter->__invoke(strtoupper($product->productName()), $change);
    }

    private function calculateTotalAmountOfFund(array $currencies, GetFundQueryResponse $fund)
    {
        $totalFund = 0.0;
        foreach ($fund->cashItems() as $fundItem) {
            foreach ($currencies as $currency) {
                if ($currency[GetCurrenciesQueryResponse::ID] == $fundItem[GetFundQueryResponse::CURRENCY_ID]) {
                    $totalFund += ($currency[GetCurrenciesQueryResponse::CURRENCY_VALUE] * $fundItem[GetFundQueryResponse::AMOUNT]);
                }
            }
        }

        return $totalFund;
    }

    private function calculateChangeDependingOnCash(float $totalChange, GetCashQueryResponse $cash, array $currencies): array
    {
        $change = [];
        usort($currencies, fn($a, $b) => $b[GetCurrenciesQueryResponse::CURRENCY_VALUE] <=> $a[GetCurrenciesQueryResponse::CURRENCY_VALUE]);

        foreach ($currencies as $currency) {
            foreach ($cash->cashItems() as $cashItem) {
                if ($currency[GetCurrenciesQueryResponse::ID] == $cashItem[GetFundQueryResponse::CURRENCY_ID]) {
                    if ($cashItem[GetFundQueryResponse::AMOUNT] > 0) {
                        $returnCurrencyAmount = $this->calculateReturnCurrencyCashAmount($totalChange, $cashItem[GetFundQueryResponse::AMOUNT], $currency[GetCurrenciesQueryResponse::CURRENCY_VALUE]);
                        $totalChange = round($totalChange - $currency[GetCurrenciesQueryResponse::CURRENCY_VALUE] * $returnCurrencyAmount, 2);
                        $change[] = [
                            "currencyId" => $cashItem[GetFundQueryResponse::CURRENCY_ID],
                            "amount" => $returnCurrencyAmount,
                            "value" => $currency[GetCurrenciesQueryResponse::CURRENCY_VALUE],
                        ];
                    }
                }
            }
        }

        if ($totalChange > 0) {
            throw new ConflictException(sprintf(
                'Insufficient cash. Missing %f to reach the return change.',
                $totalChange
            ));
        }

        return $change;
    }

    private function calculateReturnCurrencyCashAmount(float $totalChange, int $cashItemAmount, float $currencyValue): int
    {
        if ($totalChange >= $currencyValue && $cashItemAmount > 0) {
            $totalChange = round($totalChange - $currencyValue, 2);
            $cashItemAmount = $cashItemAmount - 1;
            return 1 + $this->calculateReturnCurrencyCashAmount($totalChange, $cashItemAmount, $currencyValue);
        }
        return 0;
    }
}
