<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Ticket\Domain;

use PHPUnit\Framework\TestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\Answer\AnswerId;
use TicketSystem\Ticket\Domain\Answer\EmptyAnswerException;
use TicketSystem\Ticket\Domain\Answer\ForbiddenAnswerException;
use TicketSystem\Ticket\Domain\CannotPerformActionOnTicket;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketStatus;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserRole;

class AnswerTest extends TestCase
{
    /** @test */
    public function assigned_operator_response_should_change_status_to_waiting_for_user(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $operator,
            'fsamfoamf'
        );

        self::assertEquals(TicketStatus::WAITING_FOR_USER, $ticket->status());
    }

    /** @test */
    public function not_assigned_operator_response_should_be_forbidden(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $wrongOperator = UserHelper::user(UserRole::OPERATOR, id: '611bb7dc-6623-4d3e-8561-2216f36a2a5e');

        $this->expectException(ForbiddenAnswerException::class);

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $wrongOperator,
            'fsamfoamf'
        );
    }

    /** @test */
    public function user_answered_ticket_should_switch_to_waiting_for_support(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $user = UserHelper::user(UserRole::USER);

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $operator,
            'fsamfoamf'
        );

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $user,
            'fsafafas'
        );

        self::assertEquals(TicketStatus::WAITING_FOR_SUPPORT, $ticket->status());
    }

    /** @test */
    public function answer_updates_ticket_time(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $updateTime = $ticket->updatedAt();

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $operator,
            'fsamfoamf'
        );

        self::assertNotEquals($updateTime, $ticket->updatedAt());
    }

    /** @test */
    public function super_operator_can_always_answer_a_ticket(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $superOperator = UserHelper::user(UserRole::SUPER_OPERATOR, id: '611bb7dc-6623-4d3e-8561-2216f36a2a5e');

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $superOperator,
            'fsamfoamf'
        );

        self::assertEquals(TicketStatus::WAITING_FOR_USER, $ticket->status());
    }

    /** @test */
    public function user_can_answer_multiple_times_and_status_wont_update(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $user = UserHelper::user(UserRole::USER);

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $operator,
            'fsamfoamf'
        );

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $user,
            'fsafafas'
        );

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $user,
            'fsafafas'
        );

        self::assertEquals(TicketStatus::WAITING_FOR_SUPPORT, $ticket->status());
    }

    /** @test */
    public function operator_answer_should_null_expiry_date(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $operator,
            'fsamfoamf'
        );

        self::assertNull($ticket->expiration());
    }

    /** @test */
    public function user_answer_should_change_expiration(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $user = UserHelper::user(UserRole::USER);

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $operator,
            'fsamfoamf'
        );

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $user,
            'fsafafas'
        );

        self::assertNotNull($ticket->expiration());
    }

    /** @test */
    public function answer_cannot_be_null(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $this->expectException(EmptyAnswerException::class);

        $ticket->addAnswer(
            AnswerId::create(TicketHelper::id()),
            $operator,
            ''
        );
    }

    /** @test */
    public function user_cannot_answer_if_ticket_is_closed(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $ticket->close();

        $user = UserHelper::user();
        $this->expectException(CannotPerformActionOnTicket::class);

        $ticket->addAnswer(AnswerId::create(TicketHelper::id()), $user, 'fsafsafsa');
    }

    /** @test */
    public function operator_cannot_answer_if_ticket_is_closed(): void
    {
        [$ticket, $operator] = $this->createTicket();

        $ticket->close();

        $this->expectException(CannotPerformActionOnTicket::class);

        $ticket->addAnswer(AnswerId::create(TicketHelper::id()), $operator, 'fsafsafsa');
    }

    /**
     * @return array{Ticket, User}
     */
    private function createTicket(): array
    {
        $ticket = TicketHelper::ticket();
        $operator = UserHelper::user(UserRole::OPERATOR, id: 'a0270c1c-77ba-4aa5-8a13-af23d3c56d8e');
        $ticket->assignTo($operator->operatorId());

        return [$ticket, $operator];
    }
}
