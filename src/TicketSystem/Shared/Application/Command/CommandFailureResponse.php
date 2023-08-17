<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application\Command;

final readonly class CommandFailureResponse extends FailureResponse
{
    private function __construct(public string $message, public int $status)
    {
    }

    public static function create(string $message, int $status): self
    {
        return new self($message, $status);
    }
}
