<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\Answer;

use TicketSystem\Shared\Domain\DomainException;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\User\Domain\UserId;

class ForbiddenAnswerException extends DomainException
{
    public static function create(UserId $userId, TicketId $ticketId): self
    {
        return new self(
            sprintf('User %s can\'t answer to ticket %s', $userId->id, $ticketId->id)
        );
    }
}
