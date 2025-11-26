<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Entity\ValueObject;

use VM\Shared\Domain\Service\Assertion\Assert;
use VM\Shared\Domain\ValueObject\IntegerValueObject;

final class Amount extends IntegerValueObject
{
    const MINIMUM = 0;

    public function __construct(int $value)
    {
        $this->guardValidName($value);
        parent::__construct($value);
    }

    private function guardValidName(int $value): void
    {
        Assert::greaterOrEqualThan($value,self::MINIMUM, 'Not a valid amount value');
    }
}
