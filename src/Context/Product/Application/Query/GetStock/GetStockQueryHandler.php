<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetStock;

use VM\Context\Product\Domain\Write\Repository\StockRepository;
use VM\Shared\Application\Bus\Query\QueryHandlerInterface;

class GetStockQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private StockRepository $repository,
        private GetStockQueryResponseConverter $responseConverter
    ) {
    }

    public function __invoke(GetStockQuery $query): GetStockQueryResponse
    {
        $stock = $this->repository->findVendingMachine();

        return $this->responseConverter->__invoke($stock);
    }
}
