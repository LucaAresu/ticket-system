<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

final readonly class CreateUserCommandResponse
{
    private function __construct(
        public bool $success,
        public null|string $message
    ) {
    }

    public static function create(
        bool $success,
        null|string $message = null
    ): self {
        return new self($success, $message);
    }
}
