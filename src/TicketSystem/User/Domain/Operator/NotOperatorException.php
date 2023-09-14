<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\Operator;

use TicketSystem\Shared\Domain\DomainException;
use TicketSystem\User\Domain\UserId;

class NotOperatorException extends DomainException
{
    public static function create(UserId $id): self
    {
        return new self(
            sprintf('User %s is not an operator', $id->id)
        );
    }
}
