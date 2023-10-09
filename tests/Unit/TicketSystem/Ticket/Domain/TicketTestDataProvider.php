<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Ticket\Domain;

use TicketSystem\Ticket\Domain\TicketPriority;

class TicketTestDataProvider
{
    public static function expirationBasedOnUrgency(): array
    {
        return [
            [TicketPriority::LOW, '2023-01-01 00:00:00', '2023-01-08 00:00:00'],
            [TicketPriority::MEDIUM, '2023-01-01 00:00:00', '2023-01-05 00:00:00'],
            [TicketPriority::HIGH, '2023-01-01 00:00:00', '2023-01-03 00:00:00'],
            [TicketPriority::CRITICAL, '2023-01-01 00:00:00', '2023-01-01 12:00:00'],
        ];
    }
}
