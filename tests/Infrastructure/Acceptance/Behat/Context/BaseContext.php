<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Acceptance\Behat\Context;

use Behat\Behat\Context\Context;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class BaseContext implements Context
{
    protected KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
}
