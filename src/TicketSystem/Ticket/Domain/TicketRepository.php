<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use TicketSystem\Shared\Domain\Repository;
use TicketSystem\User\Domain\Operator\OperatorId;
use TicketSystem\User\Domain\UserId;

/**
 * @template-extends Repository<Ticket, TicketId>
 */
interface TicketRepository extends Repository
{
    public function getOpenTicketsCountForUserInCategory(UserId $opener, TicketCategory $category = null): int;

    public function getNextAssignedCriticalTicketWaitingForOperator(OperatorId $operatorId, TicketCategory $ticketCategory = null): null|Ticket;

    public function getNextUnassignedCriticalTicketWaitingForOperator(TicketCategory $ticketCategory = null): null|Ticket;

    public function getNextAssignedTicketWaitingForOperator(OperatorId $operatorId, TicketCategory $ticketCategory = null): null|Ticket;

    public function getNextUnassignedTicketWaitingForOperator(TicketCategory $ticketCategory = null): null|Ticket;
}
