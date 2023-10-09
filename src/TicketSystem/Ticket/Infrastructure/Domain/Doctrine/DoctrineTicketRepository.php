<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Infrastructure\Domain\Doctrine;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\QueryBuilder;
use Ramsey\Uuid\Uuid;
use TicketSystem\Shared\Domain\EntityId;
use TicketSystem\Shared\Infrastructure\Doctrine\DoctrineRepository;
use TicketSystem\Ticket\Domain\Answer\AnswerId;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\Ticket\Domain\TicketStatus;
use TicketSystem\User\Domain\Operator\OperatorId;
use TicketSystem\User\Domain\UserId;

/**
 * @template-extends DoctrineRepository<Ticket, TicketId>
 */
class DoctrineTicketRepository extends DoctrineRepository implements TicketRepository
{
    protected function getEntityClassName(): string
    {
        return Ticket::class;
    }

    protected function getEntityAliasName(): string
    {
        return 't';
    }

    public function nextId(): EntityId
    {
        return TicketId::create(Uuid::uuid4()->toString());
    }

    public function nextAnswerId(): AnswerId
    {
        return AnswerId::create(Uuid::uuid4()->toString());
    }

    /** @psalm-suppress QueryBuilderSetParameter
     * @param UserId              $opener
     * @param null|TicketCategory $category
     */
    public function getOpenTicketsCountForUserInCategory(UserId $opener, TicketCategory $category = null): int
    {
        /** @var int $result */
        $result = $this->em->createQueryBuilder()
            ->select('COUNT(t.id) as COUNT')
            ->from($this->getEntityClassName(), $this->getEntityAliasName())
            ->andWhere('t.category = :category')
            ->setParameter('category', $category)
            ->andWhere('t.opener = :opener')
            ->setParameter('opener', $opener)
            ->andWhere('t.status != :status')
            ->setParameter('status', TicketStatus::CLOSED->value, Types::STRING)
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }

    public function getNextAssignedCriticalTicketWaitingForOperator(
        OperatorId $operatorId,
        TicketCategory $ticketCategory = null
    ): null|Ticket {
        return $this->baseNextTicketQuery(true, $ticketCategory, $operatorId);
    }

    public function getNextUnassignedCriticalTicketWaitingForOperator(
        TicketCategory $ticketCategory = null
    ): null|Ticket {
        return $this->baseNextTicketQuery(true, $ticketCategory);
    }

    public function getNextAssignedTicketWaitingForOperator(
        OperatorId $operatorId,
        TicketCategory $ticketCategory = null
    ): null|Ticket {
        return $this->baseNextTicketQuery(false, $ticketCategory, $operatorId);
    }

    public function getNextUnassignedTicketWaitingForOperator(TicketCategory $ticketCategory = null): null|Ticket
    {
        return $this->baseNextTicketQuery(false, $ticketCategory);
    }

    private function baseNextTicketQuery(
        bool $isCritical,
        null|TicketCategory $ticketCategory,
        null|OperatorId $operatorId = null
    ): null|Ticket {
        $queryBuilder = $this->addCategoryToQuery($this->createSelect(), $ticketCategory);

        /** @var null|Ticket $result */
        $result = $this->addOperatorToQuery($queryBuilder, $operatorId)
            ->andWhere('t.status = :status')
            ->setParameter('status', TicketStatus::WAITING_FOR_SUPPORT->value, Types::STRING)
            ->andWhere(sprintf('t.priority %s :priority', $isCritical ? '=' : '!='))
            ->setParameter('priority', TicketPriority::CRITICAL->value, Types::STRING)
            ->orderBy('t.expiration', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    private function addOperatorToQuery(QueryBuilder $queryBuilder, null|OperatorId $operatorId): QueryBuilder
    {
        if (null === $operatorId) {
            return $queryBuilder->andWhere('t.operator is NULL');
        }

        return $queryBuilder->andWhere('t.operator = :operator')
            ->setParameter('operator', $operatorId->id, Types::STRING);
    }

    private function addCategoryToQuery(QueryBuilder $queryBuilder, null|TicketCategory $ticketCategory): QueryBuilder
    {
        if (null === $ticketCategory) {
            return $queryBuilder;
        }

        return $queryBuilder->andWhere('t.category = :category')
            ->setParameter('category', $ticketCategory->value, Types::STRING);
    }
}
