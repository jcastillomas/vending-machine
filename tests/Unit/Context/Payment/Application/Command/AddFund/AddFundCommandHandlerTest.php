<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Command\AddFund;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Command\AddFund\AddFundCommand;
use VM\Context\Payment\Application\Command\AddFund\AddFundCommandHandler;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValueStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\FundIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\CashItemsStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Entity\ValueObject\CashItemIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\CurrencyRepositoryMock;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Repository\FundRepositoryMock;

class AddFundCommandHandlerTest extends TestCase
{
    private CurrencyRepositoryMock $currencyRepository;
    private FundRepositoryMock $fundRepository;
    private AddFundCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->currencyRepository = new CurrencyRepositoryMock($prophet->prophesize(CurrencyRepository::class));
        $this->fundRepository = new FundRepositoryMock($prophet->prophesize(FundRepository::class));
        $this->handler = new AddFundCommandHandler($this->fundRepository->reveal(), $this->currencyRepository->reveal());
    }

    public function test_it_should_add_a_currency_successfully()
    {
        $command = $this->givenACommand();
        $currency = $this->givenCurrency($command);
        $fund = $this->givenFund($currency);
        $this->thenCurrencyShouldBeFoundByCurrencyValue($command->currencyValue(), $currency);
        $this->thenFundShouldBeFoubdByCurrencyId($currency->id(), $fund);
        $this->thenFundShouldBeSaved($fund);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->currencyRepository->mock()->checkProphecyMethodsPredictions();
        $this->fundRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): AddFundCommand
    {
        return AddFundCommand::create(
            (string) CurrencyValueStub::random()->value()
        );
    }

    public function givenCurrency(AddFundCommand $command): Currency
    {
        return Currency::create(
            CurrencyIdStub::random(),
            $command->currencyValue(),
            CurrencyKindStub::random()
        );
    }

    public function givenFund(Currency $currency): Fund
    {
        $fund = Fund::create(
            FundIdStub::random(),
            VendingMachineIdStub::random(),
            CashItemsStub::random()
        );

        $fund->cashItems()->add(CashItem::create(
            CashItemIdStub::random(),
            $currency->id(),
            Amount::fromInt(0)
        ));

        return $fund;
    }

    private function thenCurrencyShouldBeFoundByCurrencyValue(CurrencyValue $currencyValue, Currency $currency)
    {
        $this->currencyRepository->shouldFindByValue($currencyValue, $currency);
    }

    private function thenFundShouldBeFoubdByCurrencyId(CurrencyId $currencyId, Fund $fund)
    {
        $this->fundRepository->findByCurrencyId($currencyId, $fund);
    }

    private function thenFundShouldBeSaved(Fund $fund)
    {
        $this->fundRepository->shouldSave($fund);
    }

    private function whenHandlingCommand(AddFundCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
