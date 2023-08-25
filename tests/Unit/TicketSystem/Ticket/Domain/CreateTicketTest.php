<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Ticket\Domain;

use Monolog\Test\TestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\CreateTicket\CreateTicket;
use TicketSystem\Ticket\Domain\CreateTicket\CreateTicketRequest;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\Ticket\Domain\TooManyTicketCreated;
use TicketSystem\Ticket\Domain\WrongRoleForCriticalTicketException;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Domain\UserRole;

class CreateTicketTest extends TestCase
{
    private CreateTicket $createTicket;
    private TicketRepository $ticketRepository;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        $this->userRepository = \Mockery::mock(UserRepository::class);
        $this->userRepository->shouldReceive('ofId')
            ->once()
            ->with(
                \Mockery::on(static fn (UserId $userId) => $userId->id === UserHelper::userId())
            )
            ->andReturn(UserHelper::user());

        $this->ticketRepository = \Mockery::mock(TicketRepository::class);
        $this->ticketRepository->shouldReceive('nextId')->andReturn(TicketId::create(TicketHelper::id()));
        $this->ticketRepository->shouldReceive('getOpenTicketsCountForUserInCategory')->andReturn(0);

        $this->createTicket = new CreateTicket(
            $this->ticketRepository,
            $this->userRepository
        );
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function it_should_create_a_ticket(): void
    {
        $this->ticketRepository->expects('save')->once();

        $ticket = $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                TicketPriority::LOW->value,
                TicketCategory::IT->value,
                UserHelper::userId()
            )
        );

        self::assertInstanceOf(TicketDto::class, $ticket);
    }

    /** @test */
    public function it_should_fail_if_user_not_found(): void
    {
        $this->userRepository->byDefault()->shouldReceive('ofId')->andReturn(null);

        $this->expectException(UserNotFoundException::class);

        $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                TicketPriority::LOW->value,
                TicketCategory::IT->value,
                UserHelper::userId()
            )
        );
    }

    /** @test */
    public function it_should_fail_when_ticket_priority_does_not_exist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                'inexisten-status',
                TicketCategory::IT->value,
                UserHelper::userId()
            )
        );
    }

    public function it_should_fail_when_ticket_category_does_not_exist(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                TicketPriority::LOW->value,
                'inexisten-category',
                UserHelper::userId()
            )
        );
    }

    /** @test */
    public function it_should_not_create_a_urgent_ticket_if_not_manager(): void
    {
        $this->userRepository
            ->byDefault()
            ->shouldReceive('ofId')
            ->once()
            ->with(
                \Mockery::on(static fn (UserId $userId) => $userId->id === UserHelper::userId())
            )
            ->andReturn(UserHelper::user(UserRole::USER));

        $this->expectException(WrongRoleForCriticalTicketException::class);

        $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                TicketPriority::CRITICAL->value,
                TicketCategory::IT->value,
                UserHelper::userId()
            )
        );
    }

    /** @test */
    public function it_should_create_a_urgent_ticket_if_manager(): void
    {
        $this->userRepository
            ->byDefault()
            ->shouldReceive('ofId')
            ->once()
            ->with(
                \Mockery::on(static fn (UserId $userId) => $userId->id === UserHelper::userId())
            )
            ->andReturn(UserHelper::user(UserRole::MANAGER));

        $this->ticketRepository->expects('save')->once();

        $ticket = $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                TicketPriority::CRITICAL->value,
                TicketCategory::IT->value,
                UserHelper::userId()
            )
        );

        self::assertInstanceOf(TicketDto::class, $ticket);
    }

    /** @test */
    public function it_should_not_possible_for_users_to_create_a_ticket_if_another_one_is_present_in_the_same_category(): void
    {
        $this->ticketRepository
            ->byDefault()
            ->shouldReceive('getOpenTicketsCountForUserInCategory')
            ->andReturn(1);

        $this->expectException(TooManyTicketCreated::class);

        $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                TicketPriority::LOW->value,
                TicketCategory::IT->value,
                UserHelper::userId()
            )
        );
    }

    /** @test */
    public function it_should_be_possible_for_managers_to_create_a_ticket_if_another_one_is_present_in_the_same_category(): void
    {
        $this->ticketRepository
            ->byDefault()
            ->shouldReceive('getOpenTicketsCountForUserInCategory')
            ->andReturn(1);

        $this->userRepository
            ->byDefault()
            ->shouldReceive('ofId')
            ->once()
            ->with(
                \Mockery::on(static fn (UserId $userId) => $userId->id === UserHelper::userId())
            )
            ->andReturn(UserHelper::user(UserRole::MANAGER));

        $this->ticketRepository->expects('save')->once();

        $ticket = $this->createTicket->execute(
            CreateTicketRequest::create(
                null,
                'asfafs',
                'fasfaffsa',
                TicketPriority::LOW->value,
                TicketCategory::IT->value,
                UserHelper::userId()
            )
        );

        self::assertInstanceOf(TicketDto::class, $ticket);
    }
}
