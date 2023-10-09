<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\NextTicket;

readonly class NextTicketCommandRequest
{
    private function __construct(public string $userId)
    {
    }

    public static function create(string $userId): self
    {
        return new self($userId);
    }
}
