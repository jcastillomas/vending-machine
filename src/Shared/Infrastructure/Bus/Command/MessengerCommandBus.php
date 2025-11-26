<?php

declare(strict_types=1);

namespace VM\Shared\Infrastructure\Bus\Command;

use VM\Shared\Application\Bus\Command\Command;
use VM\Shared\Application\Bus\Command\CommandBusInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function dispatch(
        Command $command,
    ): void {
        try {
            $stamps = [];
            $this->messageBus->dispatch(Envelope::wrap($command, $stamps));
        } catch (HandlerFailedException $exception) {
            throw current($exception->getWrappedExceptions());
        }
    }
}
