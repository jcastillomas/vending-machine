<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Command\CreateFund;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Command\CreateFund\CreateFundCommand;
use VM\Context\Payment\Application\Command\CreateFund\CreateFundCommandHandler;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\FundIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\FundRepositoryMock;

class CreateFundCommandHandlerTest extends TestCase
{
    private FundRepositoryMock $fundRepository;
    private CreateFundCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->fundRepository = new FundRepositoryMock($prophet->prophesize(FundRepository::class));
        $this->handler = new CreateFundCommandHandler($this->fundRepository->reveal());
    }

    public function test_it_should_create_a_fund_successfully()
    {
        $command = $this->givenACommand();
        $fund = $this->givenFund($command);
        $this->thenFundShouldBeSaved($fund);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->fundRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): CreateFundCommand
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

        return CreateFundCommand::create(
            FundIdStub::random()->value(),
            VendingMachineIdStub::random()->value(),
            $rawCashItems,
        );
    }

    public function givenFund(CreateFundCommand $command): Fund
    {
        return Fund::create(
            $command->id(),
            $command->vendingMachineId(),
            $command->cashItems()
        );
    }

    private function thenFundShouldBeSaved(Fund $fund)
    {
        $this->fundRepository->shouldSave($fund);
    }

    private function whenHandlingCommand(CreateFundCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
