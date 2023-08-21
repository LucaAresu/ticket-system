<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\GetOwnInfo;

final readonly class GetOwnInfoResponse
{
    private function __construct(
        public string $userId,
        public string $email
    ) {
    }

    public static function create(
        string $userId,
        string $email
    ): self {
        return new self($userId, $email);
    }
}
