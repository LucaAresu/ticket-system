<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

use TicketSystem\Shared\Application\Command;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;

/**
 * @template-implements Command<CreateUserCommandRequest, CreateUserCommandResponse>
 */
readonly class CreateUserCommand implements Command
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    /**
     * @throws UserAlreadyExistException
     * @throws \InvalidArgumentException
     */
    public function execute($request): CreateUserCommandResponse
    {
        $this->createUser($request);

        return CreateUserCommandResponse::create(true);
    }

    private function createUser(CreateUserCommandRequest $request): void
    {
        $user = User::create(
            $this->getUserId($request),
            Email::create($request->email)
        );

        $this->checkIfUserExist($user);

        $this->userRepository->save($user);
    }

    private function getUserId(CreateUserCommandRequest $request): UserId
    {
        if ($request->id) {
            return UserId::create($request->id);
        }

        return $this->userRepository->nextId();
    }

    private function checkIfUserExist(User $user): void
    {
        if (null !== $this->userRepository->ofId($user->id)) {
            throw UserAlreadyExistException::create();
        }

        if (null !== $this->userRepository->ofEmail($user->email)) {
            throw UserAlreadyExistException::create();
        }
    }
}
