<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\Login;

use TicketSystem\User\Domain\UserId;

interface StoreAccessToken
{
    public function execute(UserId $userId, #[\SensitiveParameter] string $accessToken): void;
}
