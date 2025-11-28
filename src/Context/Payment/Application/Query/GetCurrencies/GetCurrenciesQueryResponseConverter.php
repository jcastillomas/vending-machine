<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetCurrencies;


use VM\Context\Payment\Domain\Write\Aggregate\Currency;

final class GetCurrenciesQueryResponseConverter
{
    public function __invoke(array $currencies): GetCurrenciesQueryResponse
    {
        $result = [];
        /** @var Currency $currency */
        foreach ($currencies as $currency) {
            $result[] = [
                GetCurrenciesQueryResponse::ID => $currency->id()->value(),
                GetCurrenciesQueryResponse::CURRENCY_VALUE => $currency->value()->value(),
                GetCurrenciesQueryResponse::CURRENCY_KIND => $currency->kind()->value(),
            ];
        }
        return new GetCurrenciesQueryResponse($result);
    }
}
