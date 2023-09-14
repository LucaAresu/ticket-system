<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\RetrieveAccessToken;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\AccessToken\GenerateAccessToken;
use TicketSystem\User\Domain\AccessToken\WrongCredentialsException;
use TicketSystem\User\Domain\GetUserInfo;
use TicketSystem\User\Domain\UserDto;
use TicketSystem\User\Domain\UserPasswordVerifier;

/**
 * @template-implements Command<RetrieveAccessTokenCommandRequest, RetrieveAccessTokenCommandResponse>
 */
final readonly class RetrieveAccessTokenCommand implements Command
{
    // retrieve
    public function __construct(
        private GetUserInfo $getUserInfo,
        private UserPasswordVerifier $passwordVerifier,
        private GenerateAccessToken $generateAccessToken
    ) {
    }

    /**
     * @param RetrieveAccessTokenCommandRequest $request
     */
    public function execute($request): RetrieveAccessTokenCommandResponse
    {
        $user = $this->getUserInfo->execute(Email::create($request->userEmail));

        $this->verifyPassword($request, $user);

        $token = $this->generateAccessToken->execute($user->id);

        return RetrieveAccessTokenCommandResponse::create(
            $token->accessToken
        );
    }

    private function verifyPassword(RetrieveAccessTokenCommandRequest $request, UserDto $user): void
    {
        if (false === $this->passwordVerifier->execute($request->password, $user->password)) {
            throw WrongCredentialsException::create();
        }
    }
}
