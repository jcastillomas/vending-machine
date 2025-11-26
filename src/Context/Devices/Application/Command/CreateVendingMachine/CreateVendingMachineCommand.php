<?php

declare(strict_types=1);

namespace VM\Context\Devices\Application\Command\CreateVendingMachine;

use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Shared\Application\Bus\Command\Command;

class CreateVendingMachineCommand extends Command
{
    private const ID = 'id';

    public static function create(
        string $id,
    ): self {
        return new self([
            self::ID => $id,
        ]);
    }

    public function id(): VendingMachineId
    {
        return VendingMachineId::fromString($this->get(self::ID));
    }

    public static function messageName(): string
    {
        return 'command.devices.create_vending_machine';
    }
}
