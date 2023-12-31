<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Infrastructure\Communication\Http\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use TicketSystem\Shared\Application\Command\CommandHandler;
use TicketSystem\Shared\Infrastructure\Symfony\Communication\Http\Controller\Controller;
use TicketSystem\Ticket\Application\AddAnswer\AddAnswerRequest;
use TicketSystem\Ticket\Application\CloseTicket\CloseTicketCommandRequest;
use TicketSystem\Ticket\Application\CreateTicket\CreateTicketCommandRequest;
use TicketSystem\Ticket\Application\NextTicket\NextTicketCommandRequest;
use TicketSystem\Ticket\Application\NextTicket\NoNextTicketResponse;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\User\Infrastructure\Domain\Symfony\Security\DoctrineSecurityUser;

class TicketController extends Controller
{
    /**
     * @param CommandHandler<CreateTicketCommandRequest, TicketDto> $createTicketCommand
     */
    public function create(
        Request $request,
        CommandHandler $createTicketCommand,
        #[CurrentUser] DoctrineSecurityUser $user
    ): Response {
        $response = $createTicketCommand->execute(
            CreateTicketCommandRequest::create(
                ((string) $request->request->get('id')) ?: null,
                (string) $request->request->get('title'),
                (string) $request->request->get('content'),
                (string) $request->request->get('priority'),
                (string) $request->request->get('category'),
                $user->getUserIdentifier()
            )
        );

        return $this->jsonResponse($response);
    }

    /**
     * @param CommandHandler<NextTicketCommandRequest, NoNextTicketResponse|TicketDto> $nextTicketCommand
     */
    public function next(#[CurrentUser] DoctrineSecurityUser $user, CommandHandler $nextTicketCommand): Response
    {
        $response = $nextTicketCommand->execute(
            NextTicketCommandRequest::create(
                $user->getUserIdentifier()
            )
        );

        return $this->jsonResponse($response);
    }

    /**
     * @param CommandHandler<AddAnswerRequest, TicketDto> $addAnswerCommand
     */
    public function addAnswer(
        #[CurrentUser] DoctrineSecurityUser $user,
        string $ticketId,
        CommandHandler $addAnswerCommand,
        Request $request
    ): Response {
        $response = $addAnswerCommand->execute(
            AddAnswerRequest::create(
                $user->getUserIdentifier(),
                $ticketId,
                (string) $request->request->get('content'),
            )
        );

        return $this->jsonResponse($response);
    }

    /**
     * @param CommandHandler<CloseTicketCommandRequest, TicketDto> $closeTicketCommand
     */
    public function close(
        #[CurrentUser] DoctrineSecurityUser $user,
        string $ticketId,
        CommandHandler $closeTicketCommand
    ): Response {
        $response = $closeTicketCommand->execute(
            CloseTicketCommandRequest::create(
                $user->getUserIdentifier(),
                $ticketId
            )
        );

        return $this->jsonResponse($response);
    }
}
