<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\Payment\Application\Command\CreateCurrency;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;
use VM\Context\Payment\Application\Command\CreateCurrency\CreateCurrencyCommand;
use VM\Context\Payment\Application\Command\CreateCurrency\CreateCurrencyCommandHandler;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\Repository\CurrencyRepositoryMock;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyIdStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKindStub;
use VM\Tests\Infrastructure\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValueStub;

class CreateCurrencyCommandHandlerTest extends TestCase
{
    private CurrencyRepositoryMock $currencyRepository;
    private CreateCurrencyCommandHandler $handler;

    protected function setUp(): void
    {
        $prophet = new Prophet();
        $this->currencyRepository = new CurrencyRepositoryMock($prophet->prophesize(CurrencyRepository::class));
        $this->handler = new CreateCurrencyCommandHandler($this->currencyRepository->reveal());
    }

    public function test_it_should_create_a_currency_successfully()
    {
        $command = $this->givenACommand();
        $currency = $this->givenCurrency($command);
        $this->thenCurrencyShouldBeSaved($currency);
        $this->whenHandlingCommand($command);
        $this->expectNotToPerformAssertions();
        $this->currencyRepository->mock()->checkProphecyMethodsPredictions();
    }

    private function givenACommand(): CreateCurrencyCommand
    {
        return CreateCurrencyCommand::create(
            CurrencyIdStub::random()->value(),
            CurrencyValueStub::random()->value(),
            CurrencyKindStub::random()->value(),
        );
    }

    public function givenCurrency(CreateCurrencyCommand $command): Currency
    {
        return Currency::create(
            $command->id(),
            $command->value(),
            $command->kind()
        );
    }

    private function thenCurrencyShouldBeSaved(Currency $currency)
    {
        $this->currencyRepository->shouldSave($currency);
    }

    private function whenHandlingCommand(CreateCurrencyCommand $command): void
    {
        $this->handler->__invoke($command);
    }
}
