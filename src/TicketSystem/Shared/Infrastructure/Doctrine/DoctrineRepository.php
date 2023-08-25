<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Infrastructure\Doctrine;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use TicketSystem\Shared\Domain\Aggregate;
use TicketSystem\Shared\Domain\EntityId;
use TicketSystem\Shared\Domain\Repository;

/**
 * @template-covariant E of Aggregate
 * @template-covariant I of EntityId
 *
 * @template-implements Repository<E, I>
 */
abstract class DoctrineRepository implements Repository
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

    public function save(Aggregate $aggregate): void
    {
        $this->em->persist($aggregate);
        $this->em->flush();
    }

    /**
     * @throws NonUniqueResultException
     *
     * @return E
     */
    public function ofId(EntityId $id): null|Aggregate
    {
        /** @var null|E $aggregate */
        $aggregate = $this->createSelect()
            ->andWhere(sprintf('%s.id = :id', $this->getEntityAliasName()))
            ->setParameter('id', $id, \PDO::PARAM_STR)
            ->getQuery()
            ->getOneOrNullResult();

        return $aggregate;
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

    /**
     * @return I
     */
    abstract public function nextId(): EntityId;
}
