<?php

declare(strict_types=1);

namespace VM\Context\Devices\Infrastructure\Write\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;
use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;

class VendingMachineIdType extends StringType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof VendingMachineId) {
            return $value->value();
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?VendingMachineId
    {
        if (null === $value) {
            return null;
        }

        return VendingMachineId::fromString($value);
    }

    public function getName(): string
    {
        return 'vending_machine_id';
    }
}
