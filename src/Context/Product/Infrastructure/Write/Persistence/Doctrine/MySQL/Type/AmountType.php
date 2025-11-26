<?php

declare(strict_types=1);

namespace VM\Context\Product\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use VM\Context\Product\Domain\Write\Entity\ValueObject\Amount;

class AmountType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    public function getBindingType(): ParameterType
    {
        return ParameterType::INTEGER;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
    {
        if ($value instanceof Amount) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Amount
    {
        if (null === $value) {
            return null;
        }

        return Amount::fromInt($value);
    }

    public function getName(): string
    {
        return 'stock_amount';
    }
}
