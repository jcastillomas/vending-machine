<?php

declare(strict_types=1);

namespace VM\App\Application\Command\BuyItem;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Shared\Application\Bus\Command\Command;

final class BuyItemCommand extends Command
{
    public const PRODUCT_NAME = 'product_name';

    public static function create(
        string $productName,
    ): BuyItemCommand {
        return new self([
            self::PRODUCT_NAME => $productName
        ]);
    }

    public function productName(): string
    {
        return $this->get(self::PRODUCT_NAME);
    }

    protected function version(): string
    {
        return '1.0';
    }

    public static function messageName(): string
    {
        return 'command.app.buy_item';
    }
}
