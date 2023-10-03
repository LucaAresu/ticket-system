<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\Ticket\Domain;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\Answer\AnswerId;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\Ticket\Domain\TicketStatus;
use TicketSystem\User\Domain\Operator\OperatorId;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Domain\UserRole;

class TicketRepositoryTest extends KernelTestCase
{
    private TicketRepository $ticketRepository;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->ticketRepository = $container->get('TestTicketRepository');
        $this->userRepository = $container->get('TestUserRepository');
    }

    /** @test */
    public function it_should_save_a_ticket(): void
    {
        $ticket = TicketHelper::ticket(TicketPriority::LOW);

        $this->ticketRepository->save($ticket);

        $ticket2 = $this->ticketRepository->ofId(TicketId::create(TicketHelper::id()));

        self::assertTrue($ticket->isEqual($ticket2));
        self::assertEquals(TicketPriority::LOW, $ticket->priority());
        self::assertEquals(TicketStatus::WAITING_FOR_SUPPORT, $ticket->status());
        self::assertNotNull($ticket->createdAt);
    }

    /** @test */
    public function it_should_return_a_assigned_critical_ticket(): void
    {
        $ticket = TicketHelper::ticket(TicketPriority::CRITICAL, TicketCategory::HR);
        $ticket->assignTo($this->operatorId());

        $this->ticketRepository->save($ticket);

        $ticket2 = $this->ticketRepository->getNextAssignedCriticalTicketWaitingForOperator(
            $this->operatorId(),
            TicketCategory::HR
        );

        self::assertTrue($ticket->isEqual($ticket2));
    }

    /** @test */
    public function it_should_return_a_unassigned_critical_ticket(): void
    {
        $ticket = TicketHelper::ticket(TicketPriority::CRITICAL, TicketCategory::HR);

        $this->ticketRepository->save($ticket);

        $ticket2 = $this->ticketRepository->getNextUnassignedCriticalTicketWaitingForOperator(TicketCategory::HR);

        self::assertTrue($ticket->isEqual($ticket2));
    }

    /** @test */
    public function it_should_return_a_assigned_ticket(): void
    {
        $ticket = TicketHelper::ticket(category: TicketCategory::HR);
        $ticket->assignTo($this->operatorId());

        $this->ticketRepository->save($ticket);

        $ticket2 = $this->ticketRepository->getNextAssignedTicketWaitingForOperator(
            $this->operatorId(),
            TicketCategory::HR
        );

        self::assertTrue($ticket->isEqual($ticket2));
    }

    /** @test */
    public function it_should_return_an_unassigned_ticket(): void
    {
        $ticket = TicketHelper::ticket(category: TicketCategory::HR);

        $this->ticketRepository->save($ticket);

        $ticket2 = $this->ticketRepository->getNextUnassignedTicketWaitingForOperator(TicketCategory::HR);

        self::assertTrue($ticket->isEqual($ticket2));
    }

    /** @test */
    public function it_should_return_created_before_ticket_if_no_category_passed(): void
    {
        $ticket = TicketHelper::ticket(
            category: TicketCategory::HR,
            createdAt: new \DateTimeImmutable('2023-01-01 12:00:00')
        );
        $this->ticketRepository->save($ticket);
        $ticket2 = TicketHelper::ticket(
            category: TicketCategory::MARKETING,
            id: '734b0f3f-84dc-4561-ab86-357b0d9b5de2',
            createdAt: new \DateTimeImmutable('2023-01-01 00:00:00')
        );
        $this->ticketRepository->save($ticket2);

        $returnedTicket = $this->ticketRepository->getNextUnassignedTicketWaitingForOperator();

        self::assertTrue($ticket2->isEqual($returnedTicket));
    }

    private function operatorId(): OperatorId
    {
        return OperatorId::create(UserHelper::userId());
    }

    /** @test */
    public function answer_should_be_saved(): void
    {
        $ticket = TicketHelper::ticket();

        $user = UserHelper::user(UserRole::OPERATOR);
        $this->userRepository->save($user);

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $user,
            'fafsaaf'
        );

        $this->ticketRepository->save($ticket);

        $savedTicket = $this->ticketRepository->ofId($ticket->id);

        self::assertNotEmpty($savedTicket->answers());
    }
}
