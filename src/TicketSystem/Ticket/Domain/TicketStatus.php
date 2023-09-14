<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

enum TicketStatus: string
{
    case WAITING_FOR_SUPPORT = 'WAITING_FOR_SUPPORT';
    case WAITING_FOR_USER = 'WAITING_FOR_USER';
    case CLOSED = 'CLOSED';
}
