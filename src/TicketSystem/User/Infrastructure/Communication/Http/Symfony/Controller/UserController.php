<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Communication\Http\Symfony\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TicketSystem\Shared\Application\CommandHandler;
use TicketSystem\User\Application\CreateUser\CreateUserCommandRequest;
use TicketSystem\User\Application\CreateUser\CreateUserCommandResponse;

class UserController
{
    /**
     * @param CommandHandler<CreateUserCommandRequest, CreateUserCommandResponse> $createUserCommand
     */
    public function createUser(Request $request, CommandHandler $createUserCommand): Response
    {
        $response = $createUserCommand->execute(
            CreateUserCommandRequest::create(
                ((string) $request->request->get('id')) ?: null,
                (string) ($request->request->get('email') ?? '')
            )
        );

        return new JsonResponse($response);
    }
}
