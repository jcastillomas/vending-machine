<?php

declare(strict_types=1);

namespace VM\Shared\Domain\Service;

use VM\Shared\Domain\ValueObject\Uuid;

final class UuidGenerator
{
    public static function generate(): Uuid
    {
        return Uuid::fromString(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }

    public static function generateString(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
