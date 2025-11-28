<?php

declare(strict_types=1);

namespace VM\App\Application\Command\Service;

use VM\Shared\Application\Bus\Command\Command;

final class ServiceCommand extends Command
{
    public static function create(
        array $stock,
        array $cash,
    ): ServiceCommand {
        return new self([]);
    }

    protected function version(): string
    {
        return '1.0';
    }

    public static function messageName(): string
    {
        return 'command.app.service';
    }
}
