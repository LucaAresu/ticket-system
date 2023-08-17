<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Login\Redis\Predis;

use TicketSystem\User\Domain\Login\StoreAccessToken;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Infrastructure\Symfony\Security\AccessToken\AccessTokenRepository;

readonly class PredisStoreAccessToken implements StoreAccessToken
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository
    ) {
    }

    public function execute(UserId $userId, #[\SensitiveParameter] string $accessToken): void
    {
        $this->accessTokenRepository->save(
            $accessToken,
            $userId->id
        );
    }
}
