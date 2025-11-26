<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Devices\Application\Command\CreateVendingMachine;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Devices\Application\Command\CreateVendingMachine\CreateVendingMachineCommand;
use VM\Context\Devices\Application\Command\CreateVendingMachine\CreateVendingMachineCommandHandler;
use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;
use VM\Context\Devices\Domain\Write\Repository\VendingMachineRepository;
use VM\Tests\Infrastructure\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Devices\Domain\Write\Repository\VendingMachineRepositoryMock;

class CreateVendingMachineCommandHandlerTest extends TestCase
{
    private VendingMachineRepositoryMock $vendingMachineRepository;
    private CreateVendingMachineCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->vendingMachineRepository = new VendingMachineRepositoryMock($prophet->prophesize(VendingMachineRepository::class));
        $this->handler = new CreateVendingMachineCommandHandler($this->vendingMachineRepository->reveal());
    }

    public function test_it_should_create_a_vending_machine_successfully()
    {
        $command = $this->givenACommand();
        $vendingMachine = $this->givenVendingMachine($command);
        $this->thenVendingMachineShouldBeSaved($vendingMachine);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->vendingMachineRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): CreateVendingMachineCommand
    {
        return CreateVendingMachineCommand::create(
            VendingMachineIdStub::random()->value(),
        );
    }

    public function givenVendingMachine(CreateVendingMachineCommand $command): VendingMachine
    {
        return VendingMachine::create($command->id());
    }

    private function thenVendingMachineShouldBeSaved(VendingMachine $vendingMachine)
    {
        $this->vendingMachineRepository->shouldSave($vendingMachine);
    }

    private function whenHandlingCommand(CreateVendingMachineCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
