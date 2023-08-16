<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application;

final readonly class CommandFailedResponse
{
    public false $success;

    private function __construct(public string $message)
    {
        $this->success = false;
    }

    public static function create(string $message): self
    {
        return new self($message);
    }
}
