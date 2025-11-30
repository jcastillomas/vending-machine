<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Product\Application\Query\GetProduct;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Product\Application\Query\GetProduct\GetProductQuery;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryHandler;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponse;
use VM\Context\Product\Application\Query\GetProduct\GetProductQueryResponseConverter;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Repository\ProductRepository;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValueStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Repository\ProductRepositoryMock;

final class GetProductQueryHandlerTest extends TestCase
{
    private ProductRepositoryMock $productRepository;
    private GetProductQueryHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->productRepository = new ProductRepositoryMock($prophet->prophesize(ProductRepository::class));
        $this->handler = new GetProductQueryHandler($this->productRepository->reveal(), new GetProductQueryResponseConverter());
    }

    public function test_it_should_retrieve_product(): void
    {
        $product = $this->givenProduct();
        $query = $this->givenAQuery($product);
        $this->thenProductShouldBeFound($product);
        $queryResponse = $this->whenHandlingQuery($query);

        $this->assertIsArray($queryResponse->result());

        $this->assertEquals($product->id()->value(), $queryResponse->id());
        $this->assertEquals($product->name()->value(), $queryResponse->productName());
        $this->assertEquals($product->value()->value(), $queryResponse->productValue());
    }

    private function givenProduct(): Product
    {
        return Product::create(
            ProductIdStub::random(),
            ProductNameStub::random(),
            ProductValueStub::random()
        );
    }

    private function givenAQuery(Product $product): GetProductQuery
    {
        return GetProductQuery::create($product->name()->value());
    }

    private function whenHandlingQuery(GetProductQuery $query): GetProductQueryResponse
    {
        return $this->handler->__invoke($query);
    }

    private function thenProductShouldBeFound(Product $product): void
    {
        $this->productRepository->shouldFindByName($product->name(), $product);
    }
}
