<?php

declare(strict_types=1);

namespace VM\Tests\DataFixtures\DataLoaders;

use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;

abstract class Fixtures extends AbstractFixture implements ORMFixtureInterface
{
}
