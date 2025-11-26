<?php

declare(strict_types=1);

namespace VM\Shared\Domain\Message;

use InvalidArgumentException;

abstract class Message
{
    public const MESSAGE_NAME = 'name';
    public const PAYLOAD = 'payload';
    public const METADATA = 'metadata';

    public function __construct(protected array $payload, protected array $metadata = [])
    {
        $this->setPayload($payload);

        $defaultMetadata = [
            self::MESSAGE_NAME => static::messageName()
        ];
        $metadata = array_merge($defaultMetadata, $metadata);

        $this->setMetadata($metadata);
    }

    public static function fromPayload(array $payload): static
    {
        return new static($payload);
    }

    abstract public static function messageName(): string;

    public static function fromPayloadAndMetadata(array $payload, array $metadata)
    {
        return new static($payload, $metadata);
    }

    public function payload(): array
    {
        return $this->payload;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    protected function get(string $key)
    {
        if (!array_key_exists($key, $this->payload)) {
            throw new InvalidArgumentException(
                sprintf('The element with key <%s> does not exist in the payload', $key)
            );
        }

        return $this->payload[$key];
    }

    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }

    protected function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }
}
