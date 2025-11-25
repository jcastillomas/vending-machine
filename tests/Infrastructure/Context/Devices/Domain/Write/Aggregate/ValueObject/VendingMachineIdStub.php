<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Context\Devices\Domain\Write\Aggregate\ValueObject;

use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;

final class VendingMachineIdStub
{
    public static function random(): VendingMachineId
    {
        return VendingMachineId::generate();
    }
}
