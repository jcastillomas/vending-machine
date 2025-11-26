<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Command\CreateCash;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Command\CreateCash\CreateCashCommand;
use VM\Context\Payment\Application\Command\CreateCash\CreateCashCommandHandler;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CashIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\CashRepositoryMock;

class CreateCashCommandHandlerTest extends TestCase
{
    private CashRepositoryMock $currencyRepository;
    private CreateCashCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->currencyRepository = new CashRepositoryMock($prophet->prophesize(CashRepository::class));
        $this->handler = new CreateCashCommandHandler($this->currencyRepository->reveal());
    }

    public function test_it_should_create_a_currency_successfully()
    {
        $command = $this->givenACommand();
        $currency = $this->givenCash($command);
        $this->thenCashShouldBeSaved($currency);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->currencyRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): CreateCashCommand
    {
        $cashItems = CashItemsStub::random();
        $rawCashItems = [];

        /** @var CashItem $cashItem */
        foreach ($cashItems as $cashItem) {
            $rawCashItems[] = [
                'id' => $cashItem->id()->value(),
                'currencyId' => $cashItem->currencyId()->value(),
                'amount' => $cashItem->amount()->value()
            ];
        }

        return CreateCashCommand::create(
            CashIdStub::random()->value(),
            VendingMachineIdStub::random()->value(),
            $rawCashItems,
        );
    }

    public function givenCash(CreateCashCommand $command): Cash
    {
        return Cash::create(
            $command->id(),
            $command->vendingMachineId(),
            $command->cashItems()
        );
    }

    private function thenCashShouldBeSaved(Cash $currency)
    {
        $this->currencyRepository->shouldSave($currency);
    }

    private function whenHandlingCommand(CreateCashCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
