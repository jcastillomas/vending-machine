<?php

declare(strict_types=1);

namespace VM\Shared\Domain\Message;

use VM\Shared\Domain\Message\Factory\MessageFactory;

final class MessageSerializer
{
    public function __construct(private MessageFactory $messageFactory)
    {
    }

    public function serialize(Message $message): array
    {
        return [
            Message::PAYLOAD => $message->payload(),
            Message::METADATA => $message->metadata(),
        ];
    }

    public function deserialize(array $body): Message
    {
        return $this->messageFactory->create(
            $body[Message::METADATA][Message::MESSAGE_NAME],
            $body[Message::PAYLOAD],
            $body[Message::METADATA]
        );
    }
}
