<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

enum TicketPriority: string
{
    case CRITICAL = 'CRITICAL';
    case HIGH = 'HIGH';
    case MEDIUM = 'MEDIUM';
    case LOW = 'LOW';
}
