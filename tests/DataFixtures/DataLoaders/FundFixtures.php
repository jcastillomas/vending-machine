<?php

declare(strict_types=1);

namespace VM\Tests\DataFixtures\DataLoaders;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\VendingMachineId;
use VM\Context\Payment\Domain\Write\Aggregate\Fund;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\FundId;
use VM\Context\Payment\Domain\Write\Entity\CashItem;
use VM\Context\Payment\Domain\Write\Entity\CashItems;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\Amount;
use VM\Context\Payment\Domain\Write\Entity\ValueObject\CashItemId;

class FundFixtures extends Fixtures
{
    public function load(ObjectManager $manager): void
    {
        foreach (Yaml::parseFile('tests/DataFixtures/fund.yaml')['fund'] as $currencyFixture) {
            $currency = Fund::create(
                FundId::fromString($currencyFixture['id']),
                VendingMachineId::fromString($currencyFixture['vending_machine_id']),
                CashItems::create(
                    array_map(
                        fn (array $cashItem) => CashItem::create(
                            CashItemId::fromString($cashItem['id']),
                            CurrencyId::fromString($cashItem['currency_id']),
                            Amount::fromInt($cashItem['amount']),
                        ),
                    $currencyFixture['cash_items']
                    )
                )
            );
            $manager->persist($currency);
        }

        $manager->flush();
    }
}
