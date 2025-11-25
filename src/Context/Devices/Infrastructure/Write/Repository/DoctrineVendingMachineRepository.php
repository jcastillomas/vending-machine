<?php

declare(strict_types=1);

namespace VM\Context\Devices\Infrastructure\Write\Repository;

use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;
use VM\Context\Devices\Domain\Write\Repository\VendingMachineRepository;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

class DoctrineVendingMachineRepository extends AggregateRepository implements VendingMachineRepository
{
    public function save(VendingMachine $vendingMachine): void
    {
        $this->saveAggregate($vendingMachine);
    }

    public function find(VendingMachineId $vendingMachineId): VendingMachine
    {
        return $this->doFind($vendingMachineId);
    }

    protected function entityClassName(): string
    {
        return VendingMachine::class;
    }
}
