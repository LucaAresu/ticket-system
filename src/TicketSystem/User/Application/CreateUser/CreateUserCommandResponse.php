<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

final readonly class CreateUserCommandResponse
{
    private function __construct(
        public string $id,
        public string $email
    ) {
    }

    public static function create(
        string $id,
        string $email
    ): self {
        return new self($id, $email);
    }
}
