<?php

declare(strict_types=1);

namespace VM\Tests\Integration\Context\Devices\Infrastructure\Write\Persistence\Doctrine\MySQL\Repository;

use VM\Context\Devices\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Context\Devices\Domain\Write\Aggregate\VendingMachine;
use VM\Context\Devices\Domain\Write\Repository\VendingMachineRepository;
use VM\Tests\Infrastructure\Integration\MySQL\AggregateRepositoryTestCase;
use VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository\AggregateRepository;

final class DoctrineVendingMachineRepositoryTest extends AggregateRepositoryTestCase
{
    public function test_it_saves_and_finds_a_vending_machine(): void
    {
        $expectedVendingMachine = $this->givenAVendingMachineWith();
        $this->whenAVendingMachineIsSaved($expectedVendingMachine);
        $this->thenAVendingMachineIsFound($expectedVendingMachine);
    }

    private function givenAVendingMachineWith(): VendingMachine
    {
        return VendingMachine::create(VendingMachineId::generate());
    }

    private function whenAVendingMachineIsSaved(VendingMachine $vendingMachine): void
    {
        $this->repository->save($vendingMachine);
        $this->em()->flush();
        $this->em()->clear();
    }

    private function thenAVendingMachineIsFound(VendingMachine $expectedVendingMachine): void
    {
        $actualVendingMachine = $this->repository->find($expectedVendingMachine->id());
        $this->assertEquals($expectedVendingMachine->id(), $actualVendingMachine->id());
        $this->assertEquals($expectedVendingMachine->createdAt()->getTimestamp(), $actualVendingMachine->createdAt()->getTimestamp());
        $this->assertEquals($expectedVendingMachine->updatedAt()?->getTimestamp(), $actualVendingMachine->updatedAt()?->getTimestamp());
    }

    protected function repository(): AggregateRepository
    {
        return self::getContainer()->get('test.' . VendingMachineRepository::class);
    }

    protected function purge(): void
    {
        $this->purgeTables( 'vending_machine');
    }
}
