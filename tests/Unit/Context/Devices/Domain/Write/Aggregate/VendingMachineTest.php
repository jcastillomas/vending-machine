<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Devices\Domain\Write\Aggregate;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;
use VM\Tests\Infrastructure\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;

class VendingMachineTest extends TestCase
{
    public function test_it_creates_a_vending_machine(): void
    {
        $id = VendingMachineIdStub::random();
        $datetime = new DateTimeImmutable('-1 Day');

        $vendingMachine = VendingMachine::create(
            $id,
        );

        $this->assertTrue($id->equalsTo($vendingMachine->id()));
        $this->assertGreaterThan($datetime->getTimestamp(), $vendingMachine->createdAt()->getTimestamp());
        $this->assertEquals($vendingMachine->updatedAt(), null);

    }
}
