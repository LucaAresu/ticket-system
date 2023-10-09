<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

enum TicketPriority: string
{
    case CRITICAL = 'CRITICAL';
    case HIGH = 'HIGH';
    case MEDIUM = 'MEDIUM';
    case LOW = 'LOW';

    public function expirationIntervalBasedOnUrgency(): \DateInterval
    {
        return match ($this) {
            self::LOW => \DateInterval::createFromDateString('7 days'),
            self::MEDIUM => \DateInterval::createFromDateString('4 days'),
            self::HIGH => \DateInterval::createFromDateString('2 days'),
            self::CRITICAL => \DateInterval::createFromDateString('12 hours'),
        };
    }
}
