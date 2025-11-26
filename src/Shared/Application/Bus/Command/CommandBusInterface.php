<?php

declare(strict_types=1);

namespace VM\Shared\Application\Bus\Command;

interface CommandBusInterface
{
    public function dispatch(Command $command): void;
}
