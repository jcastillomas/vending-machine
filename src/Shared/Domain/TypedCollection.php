<?php

declare(strict_types=1);

namespace VM\Shared\Domain;

use VM\Shared\Domain\Service\Assertion\Assert;

abstract class TypedCollection extends Collection
{
    public function __construct(array $elements)
    {
        Assert::allIsInstanceOf($elements, $this->type());

        parent::__construct($elements);
    }

    abstract protected function type(): string;
}
