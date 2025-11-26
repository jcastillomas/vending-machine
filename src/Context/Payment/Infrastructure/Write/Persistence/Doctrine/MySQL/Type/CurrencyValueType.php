<?php

declare(strict_types=1);

namespace VM\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;

class CurrencyValueType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?float
    {
        if ($value instanceof CurrencyValue) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?CurrencyValue
    {
        if (null === $value) {
            return null;
        }

        return CurrencyValue::fromString($value);
    }

    public function getName(): string
    {
        return 'currency_value';
    }
}
