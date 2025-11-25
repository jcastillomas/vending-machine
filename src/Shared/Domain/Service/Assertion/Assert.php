<?php

declare(strict_types=1);

namespace VM\Shared\Domain\Service\Assertion;

use Assert\Assertion;
use Assert\AssertionChain;

final class Assert extends Assertion
{
    protected static $exceptionClass = AssertionFailedException::class;

    public static function uuid4(string $uuid): bool
    {
        parent::uuid($uuid);

        $uuid4pattern = '/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i';

        return parent::regex($uuid, $uuid4pattern, sprintf('Value %s is not a valid UUID4', $uuid));
    }

    public static function that($value, $defaultMessage = null, $defaultPropertyPath = null): AssertionChain
    {
        $assertionChain = new AssertionChain($value, $defaultMessage, $defaultPropertyPath);

        return $assertionChain->setAssertionClassName(self::class);
    }
}
