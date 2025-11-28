<?php

declare(strict_types=1);

namespace VM\App\Application\Command\Service;

use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

final readonly class ServiceCommandHandler implements CommandHandlerInterface
{
    public function __construct(
    ) {
    }

    public function __invoke(ServiceCommand$command): void
    {

    }
}
