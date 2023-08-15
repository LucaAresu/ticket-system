<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Email;

class User
{
    private function __construct(
        public UserId $id,
        public Email $email,
    ) {
    }

    public static function create(UserId $id, Email $email): self
    {
        return new self($id, $email);
    }

    public function isEqual(User $user): bool
    {
        return $this->id->isEqual($user->id);
    }
}
