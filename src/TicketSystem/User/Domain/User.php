<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Aggregate;
use TicketSystem\Shared\Domain\Email;

class User implements Aggregate
{
    public const NAME_MAX_LENGTH = 64;
    public const LASTNAME_MAX_LENGTH = 64;

    private function __construct(
        public readonly UserId $id,
        public readonly Email $email,
        public readonly string $name,
        public readonly string $lastname,
        #[\SensitiveParameter] private readonly string $password,
        private UserRole $role
    ) {
        $this->validate($password, $name, $lastname);
    }

    private function validate(string $password, string $name, string $lastname): void
    {
        if (!$password) {
            throw new \InvalidArgumentException('The password must not be empty');
        }

        if ('' === $name || strlen($name) > self::NAME_MAX_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Name must not be empty and not longer than %d chars', self::NAME_MAX_LENGTH));
        }

        if ('' === $lastname || strlen($lastname) > self::LASTNAME_MAX_LENGTH) {
            throw new \InvalidArgumentException(sprintf('Lastname must not be empty and not longer than %d chars', self::NAME_MAX_LENGTH));
        }
    }

    public static function create(
        UserId $id,
        Email $email,
        string $name,
        string $lastname,
        #[\SensitiveParameter] string $password,
        UserRole $role
    ): self {
        return new self($id, $email, $name, $lastname, $password, $role);
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
