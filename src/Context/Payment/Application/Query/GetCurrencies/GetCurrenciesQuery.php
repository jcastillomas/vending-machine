<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetCurrencies;

use VM\Shared\Application\Bus\Query\Query;

final class GetCurrenciesQuery extends Query
{
    public static function create(): self
    {
        return new self([]);
    }

    protected function version(): string
    {
        return '1.0';
    }

    public static function messageName(): string
    {
        return 'query.payment.get_currencies';
    }
}
