<?php

declare(strict_types=1);

namespace VM\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VM\Context\Product\Domain\Write\Entity\ValueObject\StockItemId;

class StockItemIdType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof StockItemId) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?StockItemId
    {
        if (null === $value) {
            return null;
        }

        return StockItemId::fromString($value);
    }

    public function getName(): string
    {
        return 'stock_item_id';
    }
}
