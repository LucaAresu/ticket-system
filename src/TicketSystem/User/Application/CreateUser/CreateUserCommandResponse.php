<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

final readonly class CreateUserCommandResponse
{
    public true $success;

    private function __construct(
    ) {
        $this->success = true;
    }

    public static function create(
    ): self {
        return new self();
    }
}
