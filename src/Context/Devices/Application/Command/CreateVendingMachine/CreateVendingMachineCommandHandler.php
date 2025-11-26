<?php

declare(strict_types=1);

namespace VM\Context\Devices\Application\Command\CreateVendingMachine;

use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;
use VM\Context\Devices\Domain\Write\Repository\VendingMachineRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class CreateVendingMachineCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private VendingMachineRepository $machineRepository
    ) {
    }

    public function __invoke(CreateVendingMachineCommand $command)
    {
        $this->machineRepository->save(VendingMachine::create($command->id()));
    }
}
