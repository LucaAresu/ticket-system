<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\CreateUser;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\User\Domain\CreateUser\CreateUser;
use TicketSystem\User\Domain\CreateUser\CreateUserRequest;

/**
 * @template-implements Command<CreateUserCommandRequest, CreateUserCommandResponse>
 */
readonly class CreateUserCommand implements Command
{
    public function __construct(private CreateUser $createUser)
    {
    }

    /**
     * @param CreateUserCommandRequest $request
     */
    public function execute($request): CreateUserCommandResponse
    {
        $user = $this->createUser->execute(
            CreateUserRequest::create(
                $request->id,
                $request->email,
                $request->password
            )
        );

        return CreateUserCommandResponse::create(
            $user->id->id,
            $user->email->value
        );
    }
}
