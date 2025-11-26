<?php

declare(strict_types=1);

namespace VM\Shared\Domain\Message\Factory;

use VM\Shared\Domain\Message\Message;
use VM\Shared\Domain\Service\Assertion\Assert;

final class FullyQualifiedClassNameMessageFactory implements MessageFactory
{
    private array $keyToClassNameMap;

    public function __construct()
    {
        $this->keyToClassNameMap = [];
    }

    public function create(string $messageName, array $payload, array $metadata): Message
    {
        /** @var Message $message */
        $message = $this->keyToClassNameMap[$messageName];

        return $message::fromPayloadAndMetadata($payload, $metadata);
    }

    public function addMessagesToMap(array $messageClassNames): void
    {
        Assert::allString($messageClassNames);
        Assert::allSubclassOf($messageClassNames, Message::class);

        /** @var Message $messageClassName */
        foreach ($messageClassNames as $messageClassName) {
            $this->keyToClassNameMap[$messageClassName::messageName()] = $messageClassName;
        }
    }
}
