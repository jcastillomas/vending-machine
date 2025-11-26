<?php

declare(strict_types=1);

namespace VM\Shared\Domain\ValueObject;

class FloatValueObject
{
    public function __construct(private readonly float $value)
    {
    }

    public static function fromFloat($value): static
    {
        return new static($value);
    }

    public function equalsTo(FloatValueObject $other): bool
    {
        return $this->value === $other->value()
            && get_class($this) === get_class($other);
    }

    public function value(): float
    {
        return $this->value;
    }
}
