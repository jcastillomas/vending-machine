<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetFund;

use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Shared\Application\Bus\Query\QueryHandlerInterface;

final class GetFundQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private FundRepository $repository,
        private GetFundQueryResponseConverter $responseConverter
    ) {
    }

    public function __invoke(GetFundQuery $query): GetFundQueryResponse
    {
        $fund = $this->repository->findVendingMachine();

        return $this->responseConverter->__invoke($fund);
    }
}
