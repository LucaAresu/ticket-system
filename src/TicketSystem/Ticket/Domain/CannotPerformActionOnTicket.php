<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use TicketSystem\Shared\Domain\DomainException;

class CannotPerformActionOnTicket extends DomainException
{
    public static function create(TicketId $ticketId, string $reason): self
    {
        return new self(sprintf(
            'Cannot perform action on ticket %s: reason %s',
            $ticketId->id,
            $reason
        ));
    }
}
