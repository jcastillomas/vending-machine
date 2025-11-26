<?php

declare(strict_types=1);

namespace VM\Context\Payment\Infrastructure\Write\Persistence\Doctrine\MySQL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\FundId;

class FundIdType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof FundId) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?FundId
    {
        if (null === $value) {
            return null;
        }

        return FundId::fromString($value);
    }

    public function getName(): string
    {
        return 'fund_id';
    }
}
