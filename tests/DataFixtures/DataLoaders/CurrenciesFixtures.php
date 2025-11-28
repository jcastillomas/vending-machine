<?php

declare(strict_types=1);

namespace VM\Tests\DataFixtures\DataLoaders;

use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;
use VM\Context\Payment\Domain\Write\Aggregate\Currency;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyId;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyKind;
use VM\Context\Payment\Domain\Write\Aggregate\ValueObject\CurrencyValue;

class CurrenciesFixtures extends Fixtures
{
    public function load(ObjectManager $manager): void
    {
        foreach (Yaml::parseFile('tests/DataFixtures/currencies.yaml')['currencies'] as $currencyFixture) {
            $currency = Currency::create(
                CurrencyId::fromString($currencyFixture['id']),
                CurrencyValue::fromString($currencyFixture['value']),
                CurrencyKind::fromString($currencyFixture['kind']),
            );
            $manager->persist($currency);
        }

        $manager->flush();
    }
}
