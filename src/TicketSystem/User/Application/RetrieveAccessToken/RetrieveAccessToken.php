<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\RetrieveAccessToken;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\Login\GenerateAccessToken;
use TicketSystem\User\Domain\Login\WrongCredentialsException;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserPasswordVerifier;
use TicketSystem\User\Domain\UserRepository;

/**
 * @template-implements Command<RetrieveAccessTokenRequest, RetrieveAccessTokenResponse>
 */
final readonly class RetrieveAccessToken implements Command
{
    // retrieve
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordVerifier $passwordVerifier,
        private GenerateAccessToken $generateAccessToken
    ) {
    }

    /**
     * @param RetrieveAccessTokenRequest $request
     */
    public function execute($request): RetrieveAccessTokenResponse
    {
        $user = $this->loadUser($request);

        $this->verifyPassword($request, $user);

        $token = $this->generateAccessToken->execute($user->id);

        return RetrieveAccessTokenResponse::create(
            $token->accessToken
        );
    }

    private function loadUser(RetrieveAccessTokenRequest $request): User
    {
        $user = $this->userRepository->ofEmail(Email::create($request->userEmail));

        if (null === $user) {
            throw UserNotFoundException::create($request->userEmail);
        }

        return $user;
    }

    private function verifyPassword(RetrieveAccessTokenRequest $request, User $user): void
    {
        if (false === $this->passwordVerifier->execute($request->password, $user->password)) {
            throw WrongCredentialsException::create();
        }
    }
}
