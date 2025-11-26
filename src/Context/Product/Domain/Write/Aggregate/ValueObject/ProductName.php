<?php

declare(strict_types=1);

namespace VM\Context\Product\Domain\Write\Aggregate\ValueObject;

use VM\Shared\Domain\Service\Assertion\Assert;
use VM\Shared\Domain\ValueObject\StringValueObject;

final class ProductName extends StringValueObject
{
    const MINIMUM_LENGTH = 1;

    public function __construct(string $productName)
    {
        $this->guardValidName($productName);
        parent::__construct($productName);
    }

    private function guardValidName(string $productName): void
    {
        Assert::greaterOrEqualThan(mb_strlen(trim($productName)), self::MINIMUM_LENGTH, 'Not a valid product name.');
    }
}
