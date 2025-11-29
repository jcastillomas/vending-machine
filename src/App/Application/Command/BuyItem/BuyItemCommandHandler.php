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

    public function __invoke(BuyItemCommand $command): BuyItemCommandResponse
    {
        return $this->responseConverter->__invoke("WATER", ["0.25", "0.10"]);
    }
}
