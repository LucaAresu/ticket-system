<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\CloseTicket;

readonly class CloseTicketCommandRequest
{
    private function __construct(
        public string $applicant,
        public string $ticketId
    ) {
    }

    public static function create(string $applicant, string $ticketId): self
    {
        return new self($applicant, $ticketId);
    }
}
