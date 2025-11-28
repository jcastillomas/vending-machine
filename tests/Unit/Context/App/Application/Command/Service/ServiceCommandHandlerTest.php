<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\App\Application\Command\Service;

use PHPUnit\Framework\TestCase;
use VM\App\Application\Command\Service\ServiceCommand;
use VM\App\Application\Command\Service\ServiceCommandHandler;

class ServiceCommandHandlerTest extends TestCase
{
    private ServiceCommandHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new ServiceCommandHandler();
    }

    public function test_it_should_update_stock_and_cash_successfully()
    {
        $command = $this->givenACommand();
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
    }

    private function givenACommand(): ServiceCommand
    {
        $stock = [];
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

    private function whenHandlingCommand(ServiceCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
