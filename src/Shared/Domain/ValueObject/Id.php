<?php

declare(strict_types=1);

namespace VM\Shared\Domain\ValueObject;

interface Id
{
    public static function fromString(string $string);
    public function equalsTo(Id $id): bool;
    public function value(): string;
    public function __toString(): string;
}
