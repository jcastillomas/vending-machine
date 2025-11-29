<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\App\Application\Command\Service;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\App\Application\Command\Service\ServiceCommand;
use VM\App\Application\Command\Service\ServiceCommandHandler;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommand;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommandHandler;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommand;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommandHandler;
use VM\Tests\Infrastructure\Context\Payment\Application\Command\UpdateCashCommandHandlerMock;
use VM\Tests\Infrastructure\Context\Product\Application\Command\UpdateStockCommandHandlerMock;

class ServiceCommandHandlerTest extends TestCase
{
    private ServiceCommandHandler $handler;
    private UpdateStockCommandHandlerMock $updateStockCommandHandlerMock;
    private UpdateCashCommandHandlerMock $updateCashCommandHandlerMock;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->updateStockCommandHandlerMock = new UpdateStockCommandHandlerMock($prophet->prophesize(UpdateStockCommandHandler::class));
        $this->updateCashCommandHandlerMock = new UpdateCashCommandHandlerMock($prophet->prophesize(UpdateCashCommandHandler::class));
        $this->handler = new ServiceCommandHandler(
            $this->updateStockCommandHandlerMock->reveal(),
            $this->updateCashCommandHandlerMock->reveal()
        );
    }

    public function test_it_should_update_stock_and_cash_successfully()
    {
        $command = $this->givenACommand();
        $updateStockCommand = $this->givenAUpdateStockCommand($command);
        $updateCashCommand = $this->givenAUpdateCashCommand($command);
        $this->thenUpdateStockCommandHandlerMockShouldBeInvoke($updateStockCommand);
        $this->thenUpdateCashCommandHandlerMockShouldBeInvoke($updateCashCommand);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
    }

    private function givenACommand(): ServiceCommand
    {
        $stock = [
            ServiceCommand::PRODUCT_NAME => "Water",
            ServiceCommand::STOCK => 2,
        ];
        $cash = [
            ServiceCommand::CURRENCY_VALUE => "0.10",
            ServiceCommand::AMOUNT => 2
        ];

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

    private function givenAUpdateCashCommand(ServiceCommand $command): UpdateCashCommand
    {
        return UpdateCashCommand::create(
            $command->cash()
        );
    }

    private function thenUpdateStockCommandHandlerMockShouldBeInvoke(UpdateStockCommand $command)
    {
        $this->updateStockCommandHandlerMock->shouldInvoke($command);
    }

    private function thenUpdateCashCommandHandlerMockShouldBeInvoke(UpdateCashCommand $command)
    {
        $this->updateCashCommandHandlerMock->shouldInvoke($command);
    }

    private function whenHandlingCommand(ServiceCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
