<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\Login;

use TicketSystem\User\Domain\UserId;

class GenerateAccessToken
{
    public function __construct(private StoreAccessToken $storeAccessToken)
    {
    }

    public function execute(UserId $userId): GenerateAccessTokenResponse
    {
        $token = $this->generateToken();

        $this->storeAccessToken->execute($userId, $token);

        return GenerateAccessTokenResponse::create($token);
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(30));
    }
}
