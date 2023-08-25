<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Infrastructure\Domain\Doctrine;

use Doctrine\DBAL\Types\Types;
use Ramsey\Uuid\Uuid;
use TicketSystem\Shared\Domain\EntityId;
use TicketSystem\Shared\Infrastructure\Doctrine\DoctrineRepository;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\Ticket\Domain\TicketStatus;
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

    /** @psalm-suppress QueryBuilderSetParameter */
    public function getOpenTicketsCountForUserInCategory(UserId $opener, TicketCategory $category): int
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
}
