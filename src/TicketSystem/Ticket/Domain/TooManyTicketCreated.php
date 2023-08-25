<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use TicketSystem\Shared\Domain\DomainException;

class TooManyTicketCreated extends DomainException
{
    public static function create(TicketCategory $category): self
    {
        return new self(sprintf('User have already created a ticket in category %s', $category->value));
    }
}
