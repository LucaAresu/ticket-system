<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\CloseTicket;

use TicketSystem\Ticket\Domain\CannotPerformActionOnTicket;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketNotFoundException;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\User\Domain\UserId;

readonly class CloseTicket
{
    public function __construct(private TicketRepository $ticketRepository)
    {
    }

    public function execute(CloseTicketRequest $request): TicketDto
    {
        $ticket = $this->getTicketOrFail($request->ticketId);

        if (false === $this->canClose($ticket, $request->applicant)) {
            throw CannotPerformActionOnTicket::create(
                $ticket->id,
                sprintf('User %s is not the opener', $request->applicant->id)
            );
        }

        $ticket->close();

        $this->ticketRepository->save($ticket);

        return TicketDto::createFrom($ticket);
    }

    private function getTicketOrFail(TicketId $ticketId): Ticket
    {
        $ticket = $this->ticketRepository->ofId($ticketId);

        if (null === $ticket) {
            throw TicketNotFoundException::create($ticketId->id);
        }

        return $ticket;
    }

    private function canClose(Ticket $ticket, UserId $applicant): bool
    {
        return $ticket->opener->isEqual($applicant);
    }
}
