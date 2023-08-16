<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application;

final readonly class CommandFailedResponse extends FailureResponse
{
    public false $success;

    private function __construct(public string $message, public int $status)
    {
        $this->success = false;
    }

    public static function create(string $message, int $status): self
    {
        return new self($message, $status);
    }
}
