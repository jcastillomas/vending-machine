<?php

declare(strict_types=1);

namespace VM\Shared\Application\Bus\Command;

interface Response
{
    public function result(): array;
}
