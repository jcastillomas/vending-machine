<?php

declare(strict_types=1);

namespace VM\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\CashItemId;

class CashItemIdType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof CashItemId) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CashItemId
    {
        if (null === $value) {
            return null;
        }

        return CashItemId::fromString($value);
    }

    public function getName(): string
    {
        return 'cash_item_id';
    }
}
