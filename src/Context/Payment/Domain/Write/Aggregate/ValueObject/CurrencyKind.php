<?php

declare(strict_types=1);

namespace VM\Context\Payment\Domain\Write\Aggregate\ValueObject;

use VM\Shared\Domain\Service\Assertion\Assert;
use VM\Shared\Domain\ValueObject\StringValueObject;

final class CurrencyKind extends StringValueObject
{
    const MINIMUM_LENGTH = 1;

    public function __construct(string $currencyKind)
    {
        $this->guardValidName($currencyKind);
        parent::__construct($currencyKind);
    }

    private function guardValidName(string $currencyKind): void
    {
        Assert::greaterOrEqualThan(mb_strlen(trim($currencyKind)), self::MINIMUM_LENGTH, 'Not a valid currency');
    }
}
