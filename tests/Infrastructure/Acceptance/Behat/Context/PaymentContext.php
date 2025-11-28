<?php

declare(strict_types=1);

namespace VM\Tests\Infrastructure\Acceptance\Behat\Context;

use VM\Tests\DataFixtures\DataLoaders\CurrenciesFixtures;
use VM\Tests\DataFixtures\DataLoaders\FundFixtures;
use Symfony\Component\Console\Tester\CommandTester;
use VM\Kernel;

final class PaymentContext extends AggregateContext
{
    private ?CommandTester $commandResult = null;

    public function __construct(
        Kernel $kernel,
    ) {
        parent::__construct($kernel);
    }

    /**
     * @Given /^I have currencies/
     */
    public function iHaveCurrencies(): void
    {
        $this->loadFixtures(new CurrenciesFixtures());
    }

    /**
     * @Given /^I have fund/
     */
    public function iHaveFund(): void
    {
        $this->loadFixtures(new FundFixtures());
    }

    protected function purge(): void
    {
        $this->purgeTables(
            'currency', 'fund', 'cash_item', 'fund_cash_item'
        );
    }
}
