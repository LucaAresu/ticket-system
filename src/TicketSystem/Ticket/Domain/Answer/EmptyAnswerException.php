<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\Answer;

use TicketSystem\Shared\Domain\DomainException;

class EmptyAnswerException extends DomainException
{
    public static function create(): self
    {
        return new self('Answer cannot be null');
    }
}
