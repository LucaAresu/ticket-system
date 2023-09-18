<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\NextTicket;

use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\User\Domain\Operator\OperatorId;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserRepository;

class NextTicket
{
    public function __construct(
        private readonly TicketRepository $ticketRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    public function execute(UserId $userId): null|TicketDto
    {
        $user = $this->loadUserOrFail($userId);

        $ticket = $this->loadNextTicket($user->operatorId(), $user->operatorCategory());

        if (null === $ticket) {
            return null;
        }

        $ticket->assignTo($user->operatorId());

        $this->ticketRepository->save($ticket);

        return TicketDto::createFrom($ticket);
    }

    private function loadUserOrFail(UserId $userId): User
    {
        $user = $this->userRepository->ofId($userId);

        if (null === $user) {
            throw UserNotFoundException::create($userId->id);
        }

        return $user;
    }

    private function loadNextTicket(OperatorId $operatorId, null|TicketCategory $assignedCategoryToOperator): null|Ticket
    {
        if ($ticket = $this->ticketRepository->getNextAssignedCriticalTicketWaitingForOperator($operatorId, $assignedCategoryToOperator)) {
            return $ticket;
        }

        if ($ticket = $this->ticketRepository->getNextUnassignedCriticalTicketWaitingForOperator($assignedCategoryToOperator)) {
            return $ticket;
        }

        if ($ticket = $this->ticketRepository->getNextAssignedTicketWaitingForOperator($operatorId, $assignedCategoryToOperator)) {
            return $ticket;
        }

        if ($ticket = $this->ticketRepository->getNextUnassignedTicketWaitingForOperator($assignedCategoryToOperator)) {
            return $ticket;
        }

        return null;
    }
}
