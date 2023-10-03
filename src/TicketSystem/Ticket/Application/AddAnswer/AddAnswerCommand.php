<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\AddAnswer;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketNotFoundException;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserRepository;

/**
 * @template-implements Command<AddAnswerRequest, TicketDto>
 */
readonly class AddAnswerCommand implements Command
{
    public function __construct(
        private UserRepository $userRepository,
        private TicketRepository $ticketRepository
    ) {
    }

    /**
     * @param AddAnswerRequest $request
     */
    public function execute($request): TicketDto
    {
        $user = $this->loadUserOrFail((string) $request->userId);
        $ticket = $this->loadTicketOrFail((string) $request->ticketId);

        $ticket->addAnswer(
            $this->ticketRepository->nextAnswerId(),
            $user,
            (string) $request->content
        );

        $this->ticketRepository->save($ticket);

        return TicketDto::createFrom($ticket);
    }

    private function loadUserOrFail(string $userId): User
    {
        $user = $this->userRepository->ofId(UserId::create($userId));

        if (null === $user) {
            throw UserNotFoundException::create($userId);
        }

        return $user;
    }

    private function loadTicketOrFail(string $ticketId): Ticket
    {
        $ticket = $this->ticketRepository->ofId(TicketId::create($ticketId));

        if (null === $ticket) {
            throw TicketNotFoundException::create($ticketId);
        }

        return $ticket;
    }
}
