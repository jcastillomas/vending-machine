<?php

declare(strict_types=1);

namespace VM\Context\Payment\Application\Command\CreateCash;

use VM\Context\Payment\Domain\Write\Aggregate\Cash;
use VM\Context\Payment\Domain\Write\Repository\CashRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class CreateCashCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private CashRepository $cashRepository
    ) {
    }

    public function __invoke(CreateCashCommand $command)
    {
        $this->cashRepository->save(
            Cash::create(
                $command->id(),
                $command->vendingMachineId(),
                $command->cashItems(),
            )
        );
    }
}
