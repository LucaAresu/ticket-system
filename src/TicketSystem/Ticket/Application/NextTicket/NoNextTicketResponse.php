<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\NextTicket;

readonly class NoNextTicketResponse
{
    private function __construct(public string $message)
    {
    }

    public static function create(): self
    {
        return new self('No ticket available at the moment');
    }
}
