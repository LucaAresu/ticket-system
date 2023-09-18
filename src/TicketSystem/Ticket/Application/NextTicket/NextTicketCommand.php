<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\NextTicket;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\Ticket\Domain\NextTicket\NextTicket;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\User\Domain\UserId;

/** @template-implements Command<NextTicketCommandRequest, NoNextTicketResponse|TicketDto> */
class NextTicketCommand implements Command
{
    public function __construct(
        private readonly NextTicket $nextTicket
    ) {
    }

    /**
     * @param NextTicketCommandRequest $request
     */
    public function execute($request): NoNextTicketResponse|TicketDto
    {
        $nextTicket = $this->nextTicket->execute(UserId::create($request->userId));

        return $nextTicket ?? NoNextTicketResponse::create();
    }
}
