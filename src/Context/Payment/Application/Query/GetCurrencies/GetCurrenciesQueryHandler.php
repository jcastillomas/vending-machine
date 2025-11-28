<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetCurrencies;

use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Shared\Application\Bus\Query\QueryHandlerInterface;

final class GetCurrenciesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CurrencyRepository $currencyRepository,
        private GetCurrenciesQueryResponseConverter $responseConverter
    ) {
    }

    public function __invoke(GetCurrenciesQuery $query): GetCurrenciesQueryResponse
    {
        $currencies = $this->currencyRepository->findCurrencies();

        return $this->responseConverter->__invoke($currencies);
    }
}
