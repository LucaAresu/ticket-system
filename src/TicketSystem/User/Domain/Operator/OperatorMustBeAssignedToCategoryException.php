<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\Operator;

use TicketSystem\Shared\Domain\DomainException;

class OperatorMustBeAssignedToCategoryException extends DomainException
{
    public static function create(): self
    {
        return new self('Operator must be assigned to a category');
    }
}
