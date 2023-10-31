<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\CloseTicket;

use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\User\Domain\UserId;

readonly class CloseTicketRequest
{
    private function __construct(
        public UserId $applicant,
        public TicketId $ticketId
    ) {
    }

    public static function create(UserId $applicant, TicketId $ticketId): self
    {
        return new self($applicant, $ticketId);
    }
}
