<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application;

abstract readonly class FailureResponse
{
    public int $status;
}
