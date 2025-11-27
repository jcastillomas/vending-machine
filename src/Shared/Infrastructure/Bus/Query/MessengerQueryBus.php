<?php

declare(strict_types=1);

namespace VM\Shared\Infrastructure\Bus\Query;

use VM\Shared\Application\Bus\Query\Query;
use VM\Shared\Application\Bus\Query\QueryBusInterface;
use VM\Shared\Application\Bus\Query\Response;
use VM\Shared\Domain\Service\Assertion\Assert;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBusInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function ask(Query $query): ?Response
    {
        try {
            $envelope = $this->messageBus->dispatch(new Envelope($query));

            /** @var HandledStamp|null $handledStamp */
            $handledStamp = $envelope->last(HandledStamp::class);

            if (!$handledStamp) {
                return null;
            }

            $response = $handledStamp->getResult();

            Assert::isInstanceOf($response, Response::class);

            return $response;
        } catch (HandlerFailedException $exception) {
            throw current($exception->getWrappedExceptions());
        }
    }
}
