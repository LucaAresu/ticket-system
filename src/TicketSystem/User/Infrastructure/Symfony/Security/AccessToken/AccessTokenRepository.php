<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Symfony\Security\AccessToken;

use TicketSystem\Shared\Infrastructure\Redis\Predis\Client;

class AccessTokenRepository
{
    public function __construct(private readonly Client $client, private readonly int $accessTokenExpirationInSeconds)
    {
    }

    public function getUserIdByToken(#[\SensitiveParameter] string $accessToken): null|string
    {
        return $this->client->get($accessToken);
    }

    public function save(#[\SensitiveParameter] string $accessToken, string $userId): void
    {
        $this->client->set($accessToken, $userId);
        $this->refreshAccessToken($accessToken);
    }

    public function refreshAccessToken(#[\SensitiveParameter] string $accessToken): void
    {
        if ($this->accessTokenExpirationInSeconds) {
            $this->client->expire($accessToken, $this->accessTokenExpirationInSeconds);
        }
    }
}
