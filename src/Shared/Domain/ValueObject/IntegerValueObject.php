<?php

declare(strict_types=1);

namespace VM\Shared\Domain\ValueObject;

class IntegerValueObject
{
    public function __construct(private int $value)
    {
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function equalsTo(IntegerValueObject $other): bool
    {
        return $this->value === $other->value()
            && get_class($this) === get_class($other);
    }

    public function value(): int
    {
        return $this->value;
    }
}
