<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Infrastructure\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ObjectManager;

abstract class DoctrineRepository
{
    protected EntityManagerInterface $em;

    public function __construct(
        protected Registry $registry,
    ) {
        if (!$em = $this->registry->getManagerForClass($this->getEntityClassName())) {
            throw new \LogicException('Entity Manager cannot be null');
        }

        /** @var EntityManagerInterface $em */
        $this->em = $em;
    }

    protected function createSelect(): QueryBuilder
    {
        return $this->em->createQueryBuilder()
            ->select($this->getEntityAliasName())
            ->from($this->getEntityClassName(), $this->getEntityAliasName());
    }

    /** @return class-string */
    abstract protected function getEntityClassName(): string;

    abstract protected function getEntityAliasName(): string;
}
