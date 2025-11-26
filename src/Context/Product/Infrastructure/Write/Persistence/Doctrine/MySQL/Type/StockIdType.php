<?php

declare(strict_types=1);

namespace VM\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VM\Context\Product\Domain\Write\Aggregate\ValueObject\StockId;

class StockIdType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof StockId) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?StockId
    {
        if (null === $value) {
            return null;
        }

        return StockId::fromString($value);
    }

    public function getName(): string
    {
        return 'stock_id';
    }
}
