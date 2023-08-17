<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\DomainException;

class UserNotFoundException extends DomainException
{
    public static function create(string $userIdentifier): self
    {
        return new self(
            sprintf('User not found with identifier: %s', $userIdentifier),
        );
    }
}
