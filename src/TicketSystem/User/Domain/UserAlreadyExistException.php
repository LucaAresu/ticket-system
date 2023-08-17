<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\DomainException;

class UserAlreadyExistException extends DomainException
{
    public static function create(): self
    {
        return new self('The user already exist');
    }
}
