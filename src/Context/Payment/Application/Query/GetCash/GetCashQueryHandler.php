<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetCash;

use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Shared\Application\Bus\Query\QueryHandlerInterface;

class GetCashQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CashRepository $repository,
        private GetCashQueryResponseConverter $responseConverter
    ) {
    }

    public function __invoke(GetCashQuery $query): GetCashQueryResponse
    {
        $fund = $this->repository->findVendingMachine();

        return $this->responseConverter->__invoke($fund);
    }
}
