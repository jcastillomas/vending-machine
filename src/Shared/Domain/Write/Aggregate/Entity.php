<?php

declare(strict_types=1);

namespace VM\Shared\Domain\Write\Aggregate;

use VM\Shared\Domain\ValueObject\Id;

abstract class Entity
{
    protected Id $id;

    protected function __construct(Id $id)
    {
        $this->setId($id);
    }

    private function setId(Id $id): void
    {
        $this->id = $id;
    }

    public function id(): Id
    {
        return $this->id;
    }
}
