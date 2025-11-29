<?php

declare(strict_types=1);

namespace VM\App\Application\Command\Service;

use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Shared\Application\Bus\Command\Command;

final class ServiceCommand extends Command
{
    public const STOCK = 'stock';
    public const STOCK_NAME = 'stock_name';
    public const PRODUCT_NAME = 'product_name';
    public const STOCK_AMOUNT = 'stock_amount';
    private const CASH = 'cash';
    public const AMOUNT = 'amount';
    public const CURRENCY_VALUE = 'currency_value';
    public const CASH_VALUE = 'cash_value';
    public const CASH_AMOUNT = 'cash_amount';

    public static function create(
        array $stock,
        array $cash,
    ): ServiceCommand {
        return new self([
            self::STOCK => $stock,
            self::CASH => $cash
        ]);
    }

    public function stock(): array
    {
        return array_map(
            fn (array $stockItem) => [
                self::STOCK_NAME => $stockItem[self::PRODUCT_NAME],
                self::STOCK_AMOUNT => $stockItem[self::STOCK],
            ],
            $this->get(self::STOCK)
        );
    }

    public function cash(): array
    {
        return array_map(
            fn (array $cashItem) => [
                self::CASH_VALUE => $cashItem[self::CURRENCY_VALUE],
                self::CASH_AMOUNT => $cashItem[self::AMOUNT],
            ],
            $this->get(self::CASH)
        );
    }

    protected function version(): string
    {
        return '1.0';
    }

    public static function messageName(): string
    {
        return 'command.app.service';
    }
}
