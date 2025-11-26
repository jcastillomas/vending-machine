<?php

declare(strict_types=1);

namespace VM\Shared\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use VM\Shared\Application\Bus\Command\Command;
use VM\Shared\Application\Bus\Command\CommandBusInterface;

abstract class ApiController extends AbstractController
{
    public function __construct(
        protected CommandBusInterface $commandBus
    ) {
    }

    protected function dispatch(
        Command $command,
    ): void {
        $this->commandBus->dispatch($command);
    }
}
