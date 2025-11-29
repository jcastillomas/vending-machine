<?php

declare(strict_types=1);

namespace VM\App\Application\Command\BuyItem;

final class BuyItemCommandResponseConverter
{
    public function __invoke(string $productName, array $change): BuyItemCommandResponse
    {
        $result = array_merge([$productName], $change);
        return new BuyItemCommandResponse($result);
    }
}
