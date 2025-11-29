<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\UpdateCash;

use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class UpdateCashCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CashRepository $cashRepository,
        private CurrencyRepository $currencyRepository
    ) {
    }

    public function __invoke(UpdateCashCommand $command)
    {
        $cash = $this->cashRepository->findVendingMachine();

        foreach ($command->cash() as $commandCashItem) {
            $currency = $this->currencyRepository->findByValue($commandCashItem[UpdateCashCommand::CASH_VALUE]);

            /** @var CashItem $cashItem */
            foreach ($cash->cashItems() as $cashItem) {
                if ($currency->id()->equalsTo($cashItem->currencyId())) {
                    $cashItem->setAmount($commandCashItem[UpdateCashCommand::CASH_AMOUNT]);
                }
            }
        }

        $this->cashRepository->save($cash);
    }
}
