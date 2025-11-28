<?php

declare(strict_types=1);

namespace VM\Tests\Integration\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Repository\ProductRepository;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductIdStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductValueStub;
use VM\Tests\Infrastructure\Integration\MySQL\AggregateRepositoryTestCase;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineProductRepositoryTest extends AggregateRepositoryTestCase
{
    public function test_it_saves_and_finds_a_product(): void
    {
        $expectedProduct = $this->givenAProduct();
        $this->whenAProductIsSaved($expectedProduct);
        $this->thenAProductIsFound($expectedProduct);
    }

    public function test_it_finds_by_name(): void
    {
        $expectedProduct = $this->givenAProduct();
        $this->whenAProductIsSaved($expectedProduct);
        $this->thenAProductIsFoundByProductValue($expectedProduct);
    }

    private function givenAProduct(): Product
    {
        return Product::create(
            ProductIdStub::random(),
            ProductNameStub::random(),
            ProductValueStub::random()
        );
    }

    private function whenAProductIsSaved(Product $vendingMachine): void
    {
        $this->repository->save($vendingMachine);
        $this->em()->flush();
        $this->em()->clear();
    }

    private function thenAProductIsFound(Product $expectedProduct): void
    {
        $actualProduct = $this->repository->find($expectedProduct->id());
        $this->assertEquals($expectedProduct->id(), $actualProduct->id());
        $this->assertEquals($expectedProduct->createdAt()->getTimestamp(), $actualProduct->createdAt()->getTimestamp());
        $this->assertEquals($expectedProduct->updatedAt()?->getTimestamp(), $actualProduct->updatedAt()?->getTimestamp());
    }

    private function thenAProductIsFoundByProductValue(Product $expectedProduct): void
    {
        $actualProduct = $this->repository->findByName($expectedProduct->name());
        $this->assertEquals($expectedProduct->id(), $actualProduct->id());
        $this->assertEquals($expectedProduct->createdAt()->getTimestamp(), $actualProduct->createdAt()->getTimestamp());
        $this->assertEquals($expectedProduct->updatedAt()?->getTimestamp(), $actualProduct->updatedAt()?->getTimestamp());
    }

    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . ProductRepository::class);
    }

    protected function purge(): void
    {
        $this->purgeTables( 'product');
    }
}
