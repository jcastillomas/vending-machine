<?php

declare(strict_types=1);

namespace VM\Shared\Application\Bus\Query;

interface QueryBusInterface
{
    public function ask(Query $query): ?Response;
}
