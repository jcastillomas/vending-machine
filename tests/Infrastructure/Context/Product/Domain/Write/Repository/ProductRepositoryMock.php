<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Product\Domain\Write\Repository;

use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use VM\Context\Product\Domain\Write\Aggregate\Product;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;
use VM\Context\Product\Domain\Write\Repository\ProductRepository;
use VM\Shared\Domain\Service\Assertion\Assert;

class ProductRepositoryMock
{
    private ObjectProphecy|ProductRepository $mock;

    public function __construct(ObjectProphecy $mock)
    {
        $this->mock = $mock;
    }

    public function reveal(): ProductRepository
    {
        return $this->mock->reveal();
    }

    public function mock(): ProductRepository|ObjectProphecy
    {
        return $this->mock;
    }

    public function shouldSave(Product $expectedProduct)
    {
        $this->mock
            ->save(Argument::that(function (Product $actualProduct) use ($expectedProduct) {
                Assert::eq($expectedProduct->id(), $actualProduct->id());
                Assert::eq($expectedProduct->name(), $actualProduct->name());
                Assert::eq($expectedProduct->value(), $actualProduct->value());

                return true;
            }))
            ->shouldBeCalledOnce();
    }

    public function shouldFindByName(ProductName $ProductName, Product $expectedProduct)
    {
        $this->mock
            ->findByName($ProductName)
            ->shouldBeCalledOnce()
            ->willReturn($expectedProduct);
    }
}
