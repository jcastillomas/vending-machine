<?php

declare(strict_types=1);

namespace VM\Shared\Domain\ValueObject;

use VM\Shared\Domain\Service\Assertion\Assert;
use VM\Shared\Domain\Service\UuidGenerator;

class Uuid implements Id
{
    private string $value;

    private function __construct(string $value)
    {
        $string = static::addUuid4Dashes($value);
        Assert::uuid4($string);
        $this->value = $string;
    }

    public static function fromString(string $string): static
    {
        return new static($string);
    }

    public static function generate(): static
    {
        return new static(UuidGenerator::generateString());
    }

    public function equalsTo(Id $id): bool
    {
        return $this->value === $id->value()
            && get_class($this) === get_class($id);
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function addUuid4Dashes(string $stringUuid): string
    {
        if (strpos($stringUuid, '-') > 0) {
            return $stringUuid;
        }

        return array_reduce(
            [8, 13, 18, 23],
            function (string $carry, int $position) {
                return substr_replace($carry, '-', $position, 0);
            },
            $stringUuid
        );
    }

    public function __toString(): string
    {
        return $this->value();
    }
}
