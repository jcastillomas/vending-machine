<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Query\GetProduct;

use VM\Shared\Application\Bus\Query\Response;

final readonly class GetProductQueryResponse implements Response
{
    public const ID = 'id';
    public const PRODUCT_NAME = 'product_name';
    public const PRODUCT_VALUE = 'product_value';

    public function __construct(private array $result)
    {
    }

    public function result(): array
    {
        return $this->result;
    }

    public function id(): string
    {
        return $this->result[self::ID];
    }

    public function productName(): string
    {
        return $this->result[self::PRODUCT_NAME];
    }

    public function productValue(): float
    {
        return $this->result[self::PRODUCT_VALUE];
    }
}
