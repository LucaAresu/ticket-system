<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\GetOwnInfo;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserRepository;

/**
 * @template-implements Command<string, GetOwnInfoResponse>
 */
readonly class GetOwnInfo implements Command
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @param string $request an uuid matching the user
     */
    public function execute($request): GetOwnInfoResponse
    {
        $userId = UserId::create($request);

        $user = $this->getUser($userId);

        return GetOwnInfoResponse::create(
            $user->id->id,
            $user->email->value
        );
    }

    private function getUser(UserId $userId): User
    {
        $user = $this->userRepository->ofId($userId);

        if (null === $user) {
            throw UserNotFoundException::create($userId->id);
        }

        return $user;
    }
}
