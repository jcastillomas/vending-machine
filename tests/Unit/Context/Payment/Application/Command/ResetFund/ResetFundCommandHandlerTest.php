<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Command\ResetFund;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Command\ResetFund\ResetFundCommand;
use VM\Context\Payment\Application\Command\ResetFund\ResetFundCommandHandler;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\FundIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\FundRepositoryMock;

class ResetFundCommandHandlerTest extends TestCase
{
    private FundRepositoryMock $fundRepository;
    private ResetFundCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->fundRepository = new FundRepositoryMock($prophet->prophesize(FundRepository::class));
        $this->handler = new ResetFundCommandHandler($this->fundRepository->reveal());
    }

    public function test_it_should_reset_fund_successfully()
    {
        $command = $this->givenACommand();
        $fund = $this->givenFund();
        $this->thenVendingMachineFundShouldBeFound($fund);
        $this->thenFundShouldBeSaved($fund);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->fundRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): ResetFundCommand
    {
        return ResetFundCommand::create();
    }

    public function givenFund(): Fund
    {
        $fund = Fund::create(
            FundIdStub::random(),
            VendingMachineIdStub::random(),
            CashItemsStub::random()
        );

        return $fund;
    }

    private function thenVendingMachineFundShouldBeFound(Fund $fund)
    {
        $this->fundRepository->shouldFindVendingMachine($fund);
    }

    private function thenFundShouldBeSaved(Fund $fund)
    {
        $this->fundRepository->shouldSave($fund);
    }

    private function whenHandlingCommand(ResetFundCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
