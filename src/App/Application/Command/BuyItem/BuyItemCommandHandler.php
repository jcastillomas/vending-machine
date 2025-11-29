<?php

declare(strict_types=1);

namespace VM\App\Application\Command\BuyItem;

use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

final readonly class BuyItemCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private BuyItemCommandResponseConverter $responseConverter
    ) {
    }

    /*
        TODO: @Buy item use case:
            1. Find Product by name
            2. Find Currencies
            3. Find Fund
            4. Check fund amount and product value
            5. Calculate change
            6. Find Cash
            7. Calculate change in Cash
            8. Update Stock
            9. Update Cash
           10. Reset Fund
           11. Return Response
     */
    /**
     * Execution process:
     *  1. Find Product by name
     *  2. Find Currencies
     *  3. Find Fund
     *  4. Check fund amount and product value
     *  5. Calculate change
     *  6. Find Cash
     *  7. Calculate change in Cash
     *  8. Update Stock
     *  9. Update Cash
     * 10. Reset Fund
     * 11. Return Response
     * @param BuyItemCommand $command
     * @return BuyItemCommandResponse
     */
    public function __invoke(BuyItemCommand $command): BuyItemCommandResponse
    {
        return $this->responseConverter->__invoke("WATER", ["0.25", "0.10"]);
    }
}
