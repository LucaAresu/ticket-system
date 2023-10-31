<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\CloseTicket;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\Ticket\Domain\CloseTicket\CloseTicket;
use TicketSystem\Ticket\Domain\CloseTicket\CloseTicketRequest;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\User\Domain\UserId;

/**
 * @template-implements Command<CloseTicketCommandRequest, TicketDto>
 */
class CloseTicketCommand implements Command
{
    public function __construct(
        private readonly CloseTicket $closeTicket
    ) {
    }

    /**
     * @param CloseTicketCommandRequest $request
     */
    public function execute($request): TicketDto
    {
        return $this->closeTicket->execute(
            CloseTicketRequest::create(
                UserId::create($request->applicant),
                TicketId::create($request->ticketId),
            )
        );
    }
}
