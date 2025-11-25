<?php

declare(strict_types=1);

namespace VM\Shared\Domain\Write\Aggregate;

abstract class AggregateRoot extends Entity
{
    protected int $version = 0;

    public function version(): int
    {
        return $this->version;
    }
}
