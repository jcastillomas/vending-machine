<?php

declare(strict_types=1);

namespace VM\Tests\Unit\Context\App\Application\Command\BuyItem;

use PHPUnit\Framework\TestCase;
use VM\App\Application\Command\BuyItem\BuyItemCommand;
use VM\App\Application\Command\BuyItem\BuyItemCommandHandler;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponse;
use VM\App\Application\Command\BuyItem\BuyItemCommandResponseConverter;
use VM\Tests\Infrastructure\Context\Product\Domain\Write\Aggregate\ValueObject\ProductNameStub;

final class BuyItemCommandHandlerTest extends TestCase
{
    private BuyItemCommandHandler $handler;

    protected function setUp(): void
    {
        $this->handler = new BuyItemCommandHandler(new BuyItemCommandResponseConverter());
    }

    public function test_it_should_retrieve_item_and_change(): void
    {
        $query = $this->givenACommand();
        $queryResponse = $this->whenHandlingCommand($query);
        $floatRegex = '/^[0-9\,\.]+$/i';

        $this->assertIsArray($queryResponse->result());

        foreach ($queryResponse->result() as $index => $value) {
            if ($index == 0) {
                $this->assertIsString($value);
            } else {
                $this->assertIsString($value);
                $this->assertIsFloat(floatval($value));
                $this->assertMatchesRegularExpression($floatRegex, $value);
            }
        }
    }

    private function givenACommand(): BuyItemCommand
    {
        return BuyItemCommand::create(ProductNameStub::random()->value());
    }

    private function whenHandlingCommand(BuyItemCommand $query): BuyItemCommandResponse
    {
        return $this->handler->__invoke($query);
    }
}
