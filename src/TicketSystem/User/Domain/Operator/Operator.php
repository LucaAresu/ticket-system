<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\Operator;

use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\User\Domain\User;

class Operator
{
    private function __construct(
        public readonly OperatorId $id,
        private readonly User $user,
        private null|TicketCategory $assignedCategory
    ) {
    }

    public static function create(
        OperatorId $id,
        User $user,
        null|TicketCategory $ticketCategory
    ): self {
        return new self($id, $user, $ticketCategory);
    }

    public function assignedCategory(): null|TicketCategory
    {
        return $this->assignedCategory;
    }
}
