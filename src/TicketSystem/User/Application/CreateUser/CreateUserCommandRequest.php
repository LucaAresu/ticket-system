<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

final readonly class CreateUserCommandRequest
{
    private function __construct(
        public null|string $id,
        public string $email
    ) {
    }

    public static function create(
        null|string $id,
        string $email
    ): self {
        return new self($id, $email);
    }
}
