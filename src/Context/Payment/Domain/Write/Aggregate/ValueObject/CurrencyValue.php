<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Aggregate\ValueObject;

use VM\Shared\Domain\Service\Assertion\Assert;
use VM\Shared\Domain\ValueObject\FloatValueObject;

final class CurrencyValue extends FloatValueObject
{
    const MINIMUM = 0.0;

    public function __construct(float $value)
    {
        $this->guardValidName($value);
        parent::__construct($value);
    }

    private function guardValidName(float $value): void
    {
        Assert::greaterThan($value,self::MINIMUM, 'Not a valid currency value');
    }
}
