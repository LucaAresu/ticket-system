<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\AccessToken;

use TicketSystem\Shared\Domain\DomainException;

class WrongCredentialsException extends DomainException
{
    public static function create(): self
    {
        return new self('Bad Credentials given');
    }
}
