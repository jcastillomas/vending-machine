<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Devices\Domain\Write\Aggregate;

use PHPUnit\Framework\TestCase;
use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;
use VM\Tests\Infrastructure\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;

class VendingMachineTest extends TestCase
{
    public function test_it_creates_a_vending_machine(): void
    {
        $id = VendingMachineIdStub::random();

        $vendingMachine = VendingMachine::create(
            $id,
        );

        $this->assertTrue($id->equalsTo($vendingMachine->id()));
    }
}
