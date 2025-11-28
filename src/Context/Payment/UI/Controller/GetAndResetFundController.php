<?php

declare(strict_types=1);

namespace VM\Context\Payment\UI\Controller;

use Symfony\Component\HttpFoundation\Request;
use VM\Context\Payment\Application\Command\ResetFund\ResetFundCommand;
use VM\Context\Payment\Application\Query\GetCurrencies\GetCurrenciesQuery;
use VM\Context\Payment\Application\Query\GetFund\GetFundQuery;
use VM\Shared\UI\Controller\ApiController;
use VM\Shared\UI\Response\ApiHttpResponse;
use VM\Shared\UI\Response\HttpResponseCode;

final class GetAndResetFundController extends ApiController
{
    public function __invoke(Request $request): ApiHttpResponse
    {
        $result = [];
        $fundQueryResponse = $this->ask(GetFundQuery::create());
        $currenciesQueryResponse = $this->ask(GetCurrenciesQuery::create())->result();
        usort($currenciesQueryResponse, fn ($a, $b) => $b["currency_value"] <=> $a["currency_value"]);

        foreach ($currenciesQueryResponse as $currency) {
            foreach ($fundQueryResponse->result()['cash_items'] as $cashItem) {
                if ($cashItem['currency_id'] === $currency['id']) {
                    if ($cashItem['amount'] == 0) {
                        continue;
                    }
                    foreach (range(1, $cashItem['amount']) as $count) {
                        $result[] = $currency['currency_value'];
                    }
                }
            }
        }

        $this->dispatch(ResetFundCommand::create());
        return new ApiHttpResponse($result, HttpResponseCode::HTTP_ACCEPTED);
    }
}
