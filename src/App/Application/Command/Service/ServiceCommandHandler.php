<?php

declare(strict_types=1);

namespace VM\App\Application\Command\Service;

use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommand;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommandHandler;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommand;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommandHandler;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

final readonly class ServiceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UpdateStockCommandHandler $updateStockCommandHandler,
        private UpdateCashCommandHandler $updateCashCommandHandler
    ) {
    }

    public function __invoke(ServiceCommand $command): void
    {
        $this->updateStockCommandHandler->__invoke(UpdateStockCommand::create(
            $command->stock()
        ));
        $this->updateCashCommandHandler->__invoke(UpdateCashCommand::create(
            $command->cash()
        ));
    }
}
