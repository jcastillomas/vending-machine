<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Command\UpdateCash;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommand;
use VM\Context\Payment\Application\Command\UpdateCash\UpdateCashCommandHandler;
use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CashIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValueStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\AmountStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\CashItemIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\CashRepositoryMock;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\CurrencyRepositoryMock;


class UpdateCashCommandHandlerTest extends TestCase
{
    private CurrencyRepositoryMock $currencyRepository;
    private CashRepositoryMock $cashRepository;
    private UpdateCashCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->currencyRepository = new CurrencyRepositoryMock($prophet->prophesize(CurrencyRepository::class));
        $this->cashRepository = new CashRepositoryMock($prophet->prophesize(CashRepository::class));
        $this->handler = new UpdateCashCommandHandler($this->cashRepository->reveal(), $this->currencyRepository->reveal());
    }

    public function test_it_should_update_cash_successfully()
    {
        $command = $this->givenACommand();
        $currency = $this->givenCurrency($command);
        $cash = $this->givenCash($currency);
        foreach ($command->cash() as $itemCash){
            $this->thenCurrencyShouldBeFoundByCurrencyValue($itemCash[UpdateCashCommand::CASH_VALUE], $currency);
        }
        $this->thenVandingMachineCashShouldBeFound($cash);
        $this->thenCashShouldBeSaved($cash);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->currencyRepository->mock()->checkProphecyMethodsPredictions();
        $this->cashRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): UpdateCashCommand
    {
        return UpdateCashCommand::create(
            [
                [
                    UpdateCashCommand::CASH_VALUE => CurrencyValueStub::random()->value(),
                    UpdateCashCommand::CASH_AMOUNT => AmountStub::random()->value(),
                ]
            ]
        );
    }

    public function givenCurrency(UpdateCashCommand $command): Currency
    {
        return Currency::create(
            CurrencyIdStub::random(),
            $command->cash()[0][UpdateCashCommand::CASH_VALUE],
            CurrencyKindStub::random()
        );
    }

    public function givenCash(Currency $currency): Cash
    {
        $cash = Cash::create(
            CashIdStub::random(),
            VendingMachineIdStub::random(),
            CashItemsStub::random()
        );

        $cash->cashItems()->add(CashItem::create(
            CashItemIdStub::random(),
            $currency->id(),
            Amount::fromInt(0)
        ));

        return $cash;
    }

    private function thenCurrencyShouldBeFoundByCurrencyValue(CurrencyValue $currencyName, Currency $currency)
    {
        $this->currencyRepository->shouldFindByValue($currencyName, $currency);
    }

    private function thenVandingMachineCashShouldBeFound(Cash $cash)
    {
        $this->cashRepository->shouldFindVendingMachine($cash);
    }

    private function thenCashShouldBeSaved(Cash $cash)
    {
        $this->cashRepository->shouldSave($cash);
    }

    private function whenHandlingCommand(UpdateCashCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
