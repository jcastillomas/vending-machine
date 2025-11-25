<?php

declare(strict_types=1);

namespace VM\Context\Devices\Domain\Write\Repository;

use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;

interface VendingMachineRepository
{
    public function save(VendingMachine $vendingMachine): void;
    public function find(VendingMachineId $vendingMachineId): VendingMachine;
}
