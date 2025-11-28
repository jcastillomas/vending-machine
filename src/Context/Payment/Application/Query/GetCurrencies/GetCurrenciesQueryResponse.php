<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Query\GetCurrencies;

use VM\Shared\Application\Bus\Query\Response;

final readonly class GetCurrenciesQueryResponse implements Response
{
    public const ID = 'id';
    public const CURRENCY_VALUE = 'currency_value';
    public const CURRENCY_KIND = 'currency_kind';

    public function __construct(private array $result)
    {
    }

    public function result(): array
    {
        return $this->result;
    }
}
