<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\RetrieveAccessToken;

final readonly class RetrieveAccessTokenResponse
{
    private function __construct(#[\SensitiveParameter] public string $accessToken)
    {
    }

    public static function create(#[\SensitiveParameter] string $accessToken): self
    {
        return new self($accessToken);
    }
}
