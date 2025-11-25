<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class BaseKernelTestCase extends KernelTestCase
{
    protected function tearDown(): void
    {
        //override tearDown in order to don't shut down the kernel in each test
    }
}
