<?php

declare(strict_types=1);

namespace VM\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\ProductName;

class ProductNameType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof ProductName) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ProductName
    {
        if (null === $value) {
            return null;
        }

        return ProductName::fromString($value);
    }

    public function getName(): string
    {
        return 'product_name';
    }
}
