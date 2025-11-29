<?php

declare(strict_types=1);

namespace VM\App\Application\Command\BuyItem;

use VM\Shared\Application\Bus\Command\Response;

final readonly class BuyItemCommandResponse implements Response
{
    public function __construct(private array $result)
    {
    }

    public function result(): array
    {
        return $this->result;
    }
}
