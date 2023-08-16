<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application\Command;

abstract readonly class FailureResponse
{
    public int $status;
}
