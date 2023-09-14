<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Email;

class GetUserInfo
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function execute(Email|UserId $userIdentifier): UserDto
    {
        $user = $this->getUser($userIdentifier);

        return UserDto::createFrom($user);
    }

    private function getUser(Email|UserId $userIdentifier): User
    {
        $user = $userIdentifier instanceof UserId ?
            $this->userRepository->ofId($userIdentifier) :
            $this->userRepository->ofEmail($userIdentifier);

        if (null === $user) {
            throw UserNotFoundException::create((string) $userIdentifier);
        }

        return $user;
    }
}
