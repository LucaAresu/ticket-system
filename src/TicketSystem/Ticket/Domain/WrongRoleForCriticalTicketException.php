<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use TicketSystem\Shared\Domain\DomainException;

class WrongRoleForCriticalTicketException extends DomainException
{
    public static function create(): self
    {
        return new self('Only a user of type manager can open a critical ticket');
    }
}
