<?php

declare(strict_types=1);

namespace VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository;

use Doctrine\ORM\EntityManagerInterface;
use VM\Shared\Domain\ValueObject\Id;
use VM\Shared\Domain\Write\Aggregate\AggregateRoot;
use VM\Shared\Domain\Write\Exception\AggregateNotFoundException;

abstract class AggregateRepository extends EntityRepository
{
    public function __construct(
        EntityManagerInterface $entityManager,
    ) {
        parent::__construct($entityManager);
    }

    public function saveAggregate(AggregateRoot $aggregateRoot): void
    {
        parent::saveEntity($aggregateRoot);
    }

    protected function doFind(Id $id)
    {
        $entity = self::doSearch($id);

        if (null === $entity) {
            throw AggregateNotFoundException::forId($id);
        }

        return $entity;
    }

    public function removeAggregate(AggregateRoot $aggregateRoot): void
    {
        parent::doRemove($aggregateRoot);
    }
}
