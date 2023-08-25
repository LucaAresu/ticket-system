<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

enum TicketCategory: string
{
    case HR = 'HR';
    case MARKETING = 'MARKETING';
    case IT = 'IT';
}
