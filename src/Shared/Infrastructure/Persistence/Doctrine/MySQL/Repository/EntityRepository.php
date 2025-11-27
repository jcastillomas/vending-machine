<?php

declare(strict_types=1);

namespace VM\Shared\Infrastructure\Persistence\Doctrine\MySQL\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository as DoctrineEntityRepository;
use VM\Shared\Domain\ValueObject\Id;
use VM\Shared\Domain\Write\Aggregate\Entity;
use VM\Shared\Domain\Write\Exception\EntityNotFoundException;

abstract class EntityRepository
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    protected function doSearch(Id $id)
    {
        return $this->getRepository()->find($id);
    }

    protected function doSearchByCriteria(array $criteria)
    {
        return $this->getRepository()->findBy($criteria);
    }

    protected function doFind(Id $id)
    {
        $entity = $this->doSearch($id);

        if (null === $entity) {
            throw EntityNotFoundException::forId($id);
        }

        return $entity;
    }

    protected function doRemove(Entity $entity): void
    {
        $this->entityManager->remove($entity);
    }

    protected function saveEntity($entity): void
    {
        $this->entityManager->persist($entity);
    }

    protected function flush(): void
    {
        $this->entityManager->flush();
    }

    protected function getRepository(): DoctrineEntityRepository
    {
        return $this->entityManager->getRepository($this->entityClassName());
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    abstract protected function entityClassName(): string;
}
