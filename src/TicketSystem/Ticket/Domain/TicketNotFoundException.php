<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use TicketSystem\Shared\Domain\DomainException;

class TicketNotFoundException extends DomainException
{
    public static function create(string $ticketId): self
    {
        return new self(
            sprintf('Ticket id %s not found', $ticketId)
        );
    }
}
