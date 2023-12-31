<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

final readonly class CreateUserCommandRequest
{
    private function __construct(
        public null|string $id,
        public string $email,
        public string $name,
        public string $lastname,
        #[\SensitiveParameter] public string $password
    ) {
    }

    public static function create(
        null|string $id,
        string $email,
        string $name,
        string $lastname,
        #[\SensitiveParameter] string $password
    ): self {
        return new self($id, $email, $name, $lastname, $password);
    }
}
