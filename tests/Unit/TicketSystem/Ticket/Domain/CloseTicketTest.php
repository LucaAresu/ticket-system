<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Ticket\Domain;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\CannotPerformActionOnTicket;
use TicketSystem\Ticket\Domain\CloseTicket\CloseTicket;
use TicketSystem\Ticket\Domain\CloseTicket\CloseTicketRequest;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketNotFoundException;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\Ticket\Domain\TicketStatus;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserRole;

class CloseTicketTest extends TestCase
{
    private TicketRepository $ticketRepository;
    private Ticket $ticket;
    private User $operator;
    private CloseTicket $closeTicket;

    protected function setUp(): void
    {
        $this->ticketRepository = \Mockery::mock(TicketRepository::class);

        $this->ticket = TicketHelper::ticket();
        $this->operator = UserHelper::user(UserRole::OPERATOR, id: 'a0270c1c-77ba-4aa5-8a13-af23d3c56d8e');

        $this->ticket->assignTo($this->operator->operatorId());

        $this->ticketRepository->shouldReceive('ofId')->andReturn($this->ticket);

        $this->closeTicket = new CloseTicket(
            $this->ticketRepository
        );
    }

    protected function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function user_can_close_own_ticket(): void
    {
        $this->ticketRepository->shouldReceive('save')->once()->with(\Mockery::on(
            static fn (Ticket $ticket) => true === $ticket->isClosed()
        ));

        $ticketDto = $this->closeTicket->execute(CloseTicketRequest::create(
            $this->ticket->opener,
            $this->ticket->id
        ));

        self::assertEquals(TicketStatus::CLOSED, $ticketDto->status);
    }

    /** @test */
    public function it_should_throw_if_ticket_not_found(): void
    {
        $this->ticketRepository->byDefault()->shouldReceive('ofId')->andReturn(null);

        $this->expectException(TicketNotFoundException::class);

        $this->closeTicket->execute(CloseTicketRequest::create(
            $this->ticket->opener,
            $this->ticket->id
        ));
    }

    /** @test */
    public function it_should_throw_if_someone_different_from_opener_tries_to_close(): void
    {
        $this->expectException(CannotPerformActionOnTicket::class);

        $this->closeTicket->execute(CloseTicketRequest::create(
            $this->operator->id,
            $this->ticket->id
        ));
    }
}
