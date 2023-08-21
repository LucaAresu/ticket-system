<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Symfony\Security\AccessToken;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;

readonly class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private AccessTokenRepository $accessTokenRepository,
        private UserRepository $userRepository
    ) {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        if (null === $userId = $this->accessTokenRepository->getUserIdByToken($accessToken)) {
            throw new BadCredentialsException('Invalid Token');
        }

        $this->accessTokenRepository->refreshAccessToken($accessToken);

        if (null === $this->userRepository->ofId(UserId::create($userId))) {
            throw new BadCredentialsException('User does not exist');
        }

        return new UserBadge($userId);
    }
}
