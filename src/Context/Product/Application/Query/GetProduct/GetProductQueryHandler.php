<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetProduct;

use VM\Context\Product\Domain\Write\Repository\ProductRepository;
use VM\Shared\Application\Bus\Query\QueryHandlerInterface;

class GetProductQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductRepository $repository,
        private GetProductQueryResponseConverter $responseConverter
    ) {
    }

    public function __invoke(GetProductQuery $query): GetProductQueryResponse
    {
        $product = $this->repository->findByName($query->productName());

        return $this->responseConverter->__invoke($product);
    }
}
