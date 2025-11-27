<?php

declare(strict_types=1);

namespace VM\Shared\UI\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use VM\Shared\Application\Bus\Command\Command;
use VM\Shared\Application\Bus\Command\CommandBusInterface;
use VM\Shared\Application\Bus\Query\Query;
use VM\Shared\Application\Bus\Query\QueryBusInterface;
use VM\Shared\Application\Bus\Query\Response;

abstract class ApiController extends AbstractController
{
    public function __construct(
        protected CommandBusInterface $commandBus,
        protected QueryBusInterface $queryBus,
    ) {
    }

    protected function dispatch(
        Command $command,
    ): void {
        $this->commandBus->dispatch($command);
    }

    protected function ask(Query $query): ?Response
    {
        return $this->queryBus->ask($query);
    }
}
