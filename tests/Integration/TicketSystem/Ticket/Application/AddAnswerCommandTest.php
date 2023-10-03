<?php

declare(strict_types=1);

namespace Integration\TicketSystem\Ticket\Application;

use Monolog\Test\TestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Application\AddAnswer\AddAnswerCommand;
use TicketSystem\Ticket\Application\AddAnswer\AddAnswerRequest;
use TicketSystem\Ticket\Domain\Answer\AnswerId;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketNotFoundException;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserRepository;

class AddAnswerCommandTest extends TestCase
{
    private UserRepository $userRepository;
    private TicketRepository $ticketRepository;
    private AddAnswerCommand $addAnswerCommand;

    protected function setUp(): void
    {
        $user = UserHelper::user();
        $ticket = TicketHelper::ticket();

        $this->userRepository = \Mockery::mock(UserRepository::class);
        $this->userRepository->shouldReceive('ofId')
            ->with(
                \Mockery::on(static fn (UserId $userId) => $userId->isEqual($user->id))
            )->andReturn($user);

        $this->ticketRepository = \Mockery::mock(TicketRepository::class);
        $this->ticketRepository->shouldReceive('ofId')->with(
            \Mockery::on(static fn (TicketId $ticketId) => $ticketId->isEqual($ticket->id))
        )->andReturn($ticket);

        $this->ticketRepository->shouldReceive('nextAnswerId')->andReturn(AnswerId::create(TicketHelper::id()));

        $this->addAnswerCommand = new AddAnswerCommand(
            $this->userRepository,
            $this->ticketRepository
        );
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function should_add_answer(): void
    {
        $this->ticketRepository->shouldReceive('save')->once();

        $ticketDto = $this->addAnswerCommand->execute(AddAnswerRequest::create(
            UserHelper::userId(),
            TicketHelper::id(),
            'fmsaiofsmao'
        ));

        self::assertCount(1, $ticketDto->answers);
    }

    /** @test */
    public function user_not_found_should_throw(): void
    {
        $this->userRepository->byDefault()->shouldReceive('ofId')->andReturn(null);

        $this->expectException(UserNotFoundException::class);

        $this->addAnswer();
    }

    /** @test */
    public function ticket_not_found_should_throw(): void
    {
        $this->ticketRepository->byDefault()->shouldReceive('ofId')->andReturn(null);

        $this->expectException(TicketNotFoundException::class);

        $this->addAnswer();
    }

    private function addAnswer(): TicketDto
    {
        return $this->addAnswerCommand->execute(AddAnswerRequest::create(
            UserHelper::userId(),
            TicketHelper::id(),
            'fmsaiofsmao'
        ));
    }
}
