<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Email;

final readonly class UserDto
{
    private function __construct(
        public UserId $id,
        public Email $email,
        public string $password
    ) {
    }

    public static function createFrom(User $user): self
    {
        return new self(
            $user->id,
            $user->email,
            $user->password
        );
    }
}
