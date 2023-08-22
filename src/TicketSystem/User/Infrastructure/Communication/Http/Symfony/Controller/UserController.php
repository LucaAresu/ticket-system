<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Communication\Http\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use TicketSystem\Shared\Application\Command\CommandHandler;
use TicketSystem\Shared\Infrastructure\Symfony\Communication\Http\Controller\Controller;
use TicketSystem\User\Application\CreateUser\CreateUserCommandRequest;
use TicketSystem\User\Application\CreateUser\CreateUserCommandResponse;
use TicketSystem\User\Application\RetrieveAccessToken\RetrieveAccessTokenRequest;
use TicketSystem\User\Application\RetrieveAccessToken\RetrieveAccessTokenResponse;
use TicketSystem\User\Infrastructure\Domain\Symfony\Security\DoctrineSecurityUser;

class UserController extends Controller
{
    /**
     * @param CommandHandler<CreateUserCommandRequest, CreateUserCommandResponse> $createUserCommand
     */
    public function createUser(#[CurrentUser] null|DoctrineSecurityUser $user, Request $request, CommandHandler $createUserCommand): Response
    {
        if ($user) {
            return $this->forbiddenResponse('Logged users could not access this resource');
        }

        $response = $createUserCommand->execute(
            CreateUserCommandRequest::create(
                ((string) $request->request->get('id')) ?: null,
                (string) ($request->request->get('email') ?? ''),
                (string) $request->request->get('password')
            )
        );

        return $this->jsonResponse($response);
    }

    /**
     * @param CommandHandler<RetrieveAccessTokenRequest, RetrieveAccessTokenResponse> $retrieveAccessTokenCommand
     */
    public function retrieveAccessToken(Request $request, CommandHandler $retrieveAccessTokenCommand): Response
    {
        $response = $retrieveAccessTokenCommand->execute(
            RetrieveAccessTokenRequest::create(
                (string) $request->request->get('email'),
                (string) $request->request->get('password')
            )
        );

        return $this->jsonResponse($response);
    }

    /**
     * @param CommandHandler<string, RetrieveAccessTokenResponse> $getOwnInfoCommand
     */
    public function me(#[CurrentUser] DoctrineSecurityUser $user, CommandHandler $getOwnInfoCommand): Response
    {
        $response = $getOwnInfoCommand->execute(
            $user->getUserIdentifier()
        );

        return $this->jsonResponse($response);
    }
}
