<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use TicketSystem\Shared\Domain\Repository;
use TicketSystem\User\Domain\UserId;

/**
 * @template-extends Repository<Ticket, TicketId>
 */
interface TicketRepository extends Repository
{
    public function getOpenTicketsCountForUserInCategory(UserId $opener, TicketCategory $category): int;
}
