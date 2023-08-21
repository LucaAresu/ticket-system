<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\Login;

use TicketSystem\Shared\Domain\DomainException;

class WrongCredentialsException extends DomainException
{
    public static function create(): self
    {
        return new self('Bad Credentials given');
    }
}
