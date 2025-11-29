<?php

declare(strict_types=1);

namespace VM\App\Application\Command\Service;

use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommand;
use VM\Context\Product\Application\Command\UpdateStock\UpdateStockCommandHandler;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

final readonly class ServiceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UpdateStockCommandHandler $updateStockCommandHandler
    ) {
    }

    public function __invoke(ServiceCommand $command): void
    {
        $this->updateStockCommandHandler->__invoke(UpdateStockCommand::create(
            $command->stock()
        ));
    }
}
