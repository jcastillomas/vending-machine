<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\CreateFund;

use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Repository\FundRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class CreateFundCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private FundRepository $cashRepository
    ) {
    }

    public function __invoke(CreateFundCommand $command)
    {
        $this->cashRepository->save(
            Fund::create(
                $command->id(),
                $command->vendingMachineId(),
                $command->cashItems(),
            )
        );
    }
}
