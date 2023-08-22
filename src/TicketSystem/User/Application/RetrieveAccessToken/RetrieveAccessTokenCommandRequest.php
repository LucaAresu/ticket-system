<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\RetrieveAccessToken;

final readonly class RetrieveAccessTokenCommandRequest
{
    private function __construct(
        public string $userEmail,
        public string $password
    ) {
    }

    public static function create(string $userEmail, string $password): self
    {
        return new self($userEmail, $password);
    }
}
