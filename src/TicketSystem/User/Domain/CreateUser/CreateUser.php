<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain\CreateUser;

use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserAlreadyExistException;
use TicketSystem\User\Domain\UserDto;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserPasswordHasher;
use TicketSystem\User\Domain\UserRepository;

readonly class CreateUser
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasher $passwordHasher
    ) {
    }

    /**
     * @throws UserAlreadyExistException
     * @throws \InvalidArgumentException
     */
    public function execute(CreateUserRequest $request): UserDto
    {
        $user = $this->createUser($request);

        return UserDto::createFrom($user);
    }

    private function createUser(CreateUserRequest $request): User
    {
        $user = User::create(
            $this->getUserId($request),
            Email::create($request->email),
            $request->name,
            $request->lastname,
            $this->passwordHasher->execute($request->password)
        );

        $this->checkIfUserExist($user);

        $this->userRepository->save($user);

        return $user;
    }

    private function getUserId(CreateUserRequest $request): UserId
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
