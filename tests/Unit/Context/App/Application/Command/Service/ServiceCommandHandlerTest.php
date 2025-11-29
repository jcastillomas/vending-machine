<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\App\Application\Command\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\App\Application\Command\Service\ServiceCommand;
use VM\App\Application\Command\Service\ServiceCommandHandler;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommand;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommandHandler;
use VM\Tests\Infrastructure\Context\Product\Application\Command\UpdateStockCommandHandlerMock;

class ServiceCommandHandlerTest extends TestCase
{
    private ServiceCommandHandler $handler;
    private UpdateStockCommandHandlerMock $updateStockCommandHandlerMock;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->updateStockCommandHandlerMock = new UpdateStockCommandHandlerMock($prophet->prophesize(UpdateStockCommandHandler::class));
        $this->handler = new ServiceCommandHandler($this->updateStockCommandHandlerMock->reveal());
    }

    public function test_it_should_update_stock_and_cash_successfully()
    {
        $command = $this->givenACommand();
        $updateStockCommand = $this->givenAUpdateStockCommand($command);
        $this->thenUpdateStockCommandHandlerMockShouldBeInvoke($updateStockCommand);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
    }

    private function givenACommand(): ServiceCommand
    {
        $stock = [
            ServiceCommand::PRODUCT_NAME => 'Water',
            ServiceCommand::STOCK => 2,
        ];
        $cash = [];

        return ServiceCommand::create(
            [
                $stock
            ],
            [
                $cash
            ]
        );
    }

    private function givenAUpdateStockCommand(ServiceCommand $command): UpdateStockCommand
    {
        return UpdateStockCommand::create(
            $command->stock()
        );
    }

    private function thenUpdateStockCommandHandlerMockShouldBeInvoke(UpdateStockCommand $command)
    {
        $this->updateStockCommandHandlerMock->shouldInvoke($command);
    }

    private function whenHandlingCommand(ServiceCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
