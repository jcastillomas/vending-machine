<?php

declare(strict_types=1);

namespace VM\Context\Devices\Domain\Write\Aggregate;

use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Shared\Domain\Write\Aggregate\AggregateRoot;

class VendingMachine extends AggregateRoot
{
    public static function create(
        VendingMachineId $vendingMachineId
    ): self {
        return new self($vendingMachineId);
    }
}
