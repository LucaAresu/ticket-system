<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\CreateTicket;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\Ticket\Domain\CreateTicket\CreateTicket;
use TicketSystem\Ticket\Domain\CreateTicket\CreateTicketRequest;
use TicketSystem\Ticket\Domain\TicketDto;

/**
 * @template-implements Command<CreateTicketCommandRequest, TicketDto>
 */
class CreateTicketCommand implements Command
{
    public function __construct(private readonly CreateTicket $createTicket)
    {
    }

    /**
     * @param CreateTicketCommandRequest $request
     */
    public function execute($request): TicketDto
    {
        return $this->createTicket->execute(
            CreateTicketRequest::create(
                $request->id,
                $request->title,
                $request->content,
                $request->priority,
                $request->category,
                $request->opener
            )
        );
    }
}
