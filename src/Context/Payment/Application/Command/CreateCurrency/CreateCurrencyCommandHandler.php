<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\CreateCurrency;

use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class CreateCurrencyCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CurrencyRepository $currencyRepository
    ) {
    }

    public function __invoke(CreateCurrencyCommand $command)
    {
        $this->currencyRepository->save(
            Currency::create(
                $command->id(),
                $command->value(),
                $command->kind(),
            )
        );
    }
}
