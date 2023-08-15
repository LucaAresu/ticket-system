<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Communication\Http\Symfony\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TicketSystem\User\Application\CreateUser\CreateUserCommand;
use TicketSystem\User\Application\CreateUser\CreateUserCommandRequest;

class UserController
{
    public function createUser(Request $request, CreateUserCommand $createUserCommand): Response
    {
        $response = $createUserCommand->execute(CreateUserCommandRequest::create(
            ((string) $request->request->get('id')) ?: null,
            (string) ($request->request->get('email') ?? '')
        ));

        return new JsonResponse($response);
    }
}
