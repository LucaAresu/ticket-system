<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Email;

class User
{
    private function __construct(
        public readonly UserId $id,
        public readonly Email $email,
        #[\SensitiveParameter] private string $password,
        private UserRole $role
    ) {
    }

    public static function create(UserId $id, Email $email, #[\SensitiveParameter] string $password, UserRole $role): self
    {
        if (!$password) {
            throw new \InvalidArgumentException('The password must not be empty');
        }

        return new self($id, $email, $password, $role);
    }

    public function isEqual(User $user): bool
    {
        return $this->id->isEqual($user->id);
    }

    public function password(): string
    {
        return $this->password;
    }

    public function role(): UserRole
    {
        return $this->role;
    }
}
