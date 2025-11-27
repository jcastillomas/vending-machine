<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\AddFund;

use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class AddFundCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private FundRepository $fundRepository,
        private CurrencyRepository $currencyRepository
    ) {
    }

    public function __invoke(AddFundCommand $command)
    {
        $currency = $this->currencyRepository->findByValue($command->currencyValue());
        $fund = $this->fundRepository->findByCurrencyId($currency->id());

        /** @var CashItem $cashItem */
        foreach ($fund->cashItems() as $cashItem) {
            if ($currency->id()->equalsTo($cashItem->currencyId())) {
                $cashItem->addAmount();
            }
        }

        $this->fundRepository->save($fund);
    }
}
