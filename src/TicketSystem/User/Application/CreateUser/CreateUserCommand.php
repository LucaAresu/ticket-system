<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

use TicketSystem\Shared\Domain\DomainException;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;

readonly class CreateUserCommand
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function execute(CreateUserCommandRequest $request): CreateUserCommandResponse
    {
        try {
            $this->createUser($request);

            return CreateUserCommandResponse::create(true);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse(
                sprintf('A validation error occurred: %s', $e->getMessage())
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage());
        } catch (\Throwable $e) {
            // todo notify
            return $this->errorResponse('Internal Error');
        }
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

    private function errorResponse(string $error): CreateUserCommandResponse
    {
        return CreateUserCommandResponse::create(
            false,
            $error
        );
    }
}
