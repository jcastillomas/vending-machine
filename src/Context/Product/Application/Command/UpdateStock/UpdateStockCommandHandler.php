<?php

declare(strict_types=1);

namespace VM\Context\Product\Application\Command\UpdateStock;

use VM\Context\Product\Domain\Write\Entity\StockItem;
use VM\Context\Product\Domain\Write\Repository\ProductRepository;
use VM\Context\Product\Domain\Write\Repository\StockRepository;
use VM\Shared\Application\Bus\Command\CommandHandlerInterface;

class UpdateStockCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private StockRepository $stockRepository,
        private ProductRepository $productRepository
    ) {
    }

    public function __invoke(UpdateStockCommand $command)
    {
        $stock = $this->stockRepository->findVendingMachine();

        foreach ($command->stock() as $commandStockItem) {
            $product = $this->productRepository->findByName($commandStockItem[UpdateStockCommand::STOCK_NAME]);

            /** @var StockItem $stockItem */
            foreach ($stock->stockItems() as $stockItem) {
                if ($product->id()->equalsTo($stockItem->productId())) {
                    $stockItem->setAmount($commandStockItem[UpdateStockCommand::STOCK_AMOUNT]);
                }
            }
        }

        $this->stockRepository->save($stock);
    }
}
