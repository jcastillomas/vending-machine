<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\ResetFund;

use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Repository\CurrencyRepository;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class ResetFundCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private FundRepository $fundRepository,
    ) {
    }

    public function __invoke(ResetFundCommand $command)
    {
        $fund = $this->fundRepository->findVendingMachine();

        /** @var CashItem $cashItem */
        foreach ($fund->cashItems() as $cashItem) {
            $cashItem->resetAmount();
        }

        $this->fundRepository->save($fund);
    }
}
