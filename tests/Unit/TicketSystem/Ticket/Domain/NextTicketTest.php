<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Ticket\Domain;

use Monolog\Test\TestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\NextTicket\NextTicket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\User\Domain\Operator\NotOperatorException;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Domain\UserRole;

class NextTicketTest extends TestCase
{
    private TicketRepository $ticketRepository;
    private UserRepository $userRepository;
    private NextTicket $nextTicket;

    protected function setUp(): void
    {
        $this->ticketRepository = \Mockery::mock(TicketRepository::class);
        $this->userRepository = \Mockery::mock(UserRepository::class);

        $user = UserHelper::user();
        $user->become(UserRole::OPERATOR, TicketCategory::HR);

        $this->userRepository->shouldReceive('ofId')->andReturn($user);

        $this->ticketRepository->shouldReceive('save')->once();

        $this->nextTicket = new NextTicket($this->ticketRepository, $this->userRepository);
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function it_should_throw_if_user_not_found(): void
    {
        $this->userRepository->byDefault()->shouldReceive('ofId')->andReturn(null);
        $this->ticketRepository->byDefault()->shouldReceive('save')->times(0);

        $this->expectException(UserNotFoundException::class);

        $this->nextTicket->execute(UserId::create(UserHelper::userId()));
    }

    /** @test */
    public function it_should_throw_if_user_is_not_operator(): void
    {
        $this->userRepository->byDefault()->shouldReceive('ofId')->andReturn(UserHelper::user());
        $this->ticketRepository->byDefault()->shouldReceive('save')->times(0);

        $this->expectException(NotOperatorException::class);

        $this->nextTicket->execute(UserId::create(UserHelper::userId()));
    }

    /** @test */
    public function it_should_return_critical_ticket_if_available_assigned_to_operator(): void
    {
        $ticket = TicketHelper::ticket();

        $this->ticketRepository->shouldReceive('getNextAssignedCriticalTicketWaitingForOperator')->andReturn($ticket);

        $result = $this->nextTicket->execute(UserId::create(UserHelper::userId()));
        self::assertEquals(UserHelper::userId(), $result->operator);
    }

    /** @test */
    public function it_should_return_unassigned_critical_ticket(): void
    {
        $ticket = TicketHelper::ticket();

        $this->ticketRepository->shouldReceive('getNextAssignedCriticalTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextUnassignedCriticalTicketWaitingForOperator')->andReturn($ticket);

        $result = $this->nextTicket->execute(UserId::create(UserHelper::userId()));
        self::assertEquals(UserHelper::userId(), $result->operator);
    }

    /** @test */
    public function it_should_return_assigned_not_critical_ticket(): void
    {
        $ticket = TicketHelper::ticket();

        $this->ticketRepository->shouldReceive('getNextAssignedCriticalTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextUnassignedCriticalTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextAssignedTicketWaitingForOperator')->andReturn($ticket);

        $result = $this->nextTicket->execute(UserId::create(UserHelper::userId()));
        self::assertEquals(UserHelper::userId(), $result->operator);
    }

    /** @test */
    public function it_should_return_unassigned_not_critical_ticket(): void
    {
        $ticket = TicketHelper::ticket();

        $this->ticketRepository->shouldReceive('getNextAssignedCriticalTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextUnassignedCriticalTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextAssignedTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextUnassignedTicketWaitingForOperator')->andReturn($ticket);

        $result = $this->nextTicket->execute(UserId::create(UserHelper::userId()));

        self::assertEquals(UserHelper::userId(), $result->operator);
    }

    /** @test */
    public function it_should_return_null_if_no_ticket_available(): void
    {
        $this->ticketRepository->shouldReceive('getNextAssignedCriticalTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextUnassignedCriticalTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextAssignedTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->shouldReceive('getNextUnassignedTicketWaitingForOperator')->andReturn(null);
        $this->ticketRepository->byDefault()->shouldReceive('save')->times(0);

        $result = $this->nextTicket->execute(UserId::create(UserHelper::userId()));

        self::assertNull($result);
    }
}
