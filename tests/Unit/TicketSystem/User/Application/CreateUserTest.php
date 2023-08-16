<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Application;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TicketSystem\User\Application\CreateUser\CreateUserCommand;
use TicketSystem\User\Application\CreateUser\CreateUserCommandRequest;
use TicketSystem\User\Application\CreateUser\UserAlreadyExistException;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;

class CreateUserTest extends KernelTestCase
{
    private CreateUserCommand $command;
    private UserRepository $repository;

    public function setUp(): void
    {
        $this->repository = \Mockery::mock(UserRepository::class);

        $this->repository->shouldReceive('nextId')->once()->andReturn(
            UserId::create('2dc415af-1c4c-43e5-83b6-b4f4bd7e3e58')
        );

        $this->repository->shouldReceive('ofId')->zeroOrMoreTimes()->andReturn(null);
        $this->repository->shouldReceive('ofEmail')->zeroOrMoreTimes()->andReturn(null);

        $this->command = new CreateUserCommand(
            $this->repository
        );
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function it_should_create_an_user(): void
    {
        $this->repository->shouldReceive('save')->once();
        $this->repository->byDefault()->shouldReceive('nextId')->never();

        $response = $this->command->execute(
            CreateUserCommandRequest::create(
                '2dc415af-1c4c-43e5-83b6-b4f4bd7e3e58',
                'prova@example.net'
            )
        );

        self::assertTrue($response->success);
    }

    /** @test */
    public function it_should_give_response_fail(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->command->execute(
            CreateUserCommandRequest::create(
                null,
                'wrong-email'
            )
        );
    }

    /** @test */
    public function it_should_error_when_a_user_with_id_already_exist(): void
    {
        $this->repository->byDefault()->shouldReceive('ofId')->once()->andReturn(\Mockery::mock(User::class));

        $this->expectException(UserAlreadyExistException::class);

        $this->command->execute(
            CreateUserCommandRequest::create(
                null,
                'prova@example.net'
            )
        );
    }

    /** @test */
    public function it_should_error_when_a_user_with_email_already_exist(): void
    {
        $this->repository->byDefault()->shouldReceive('ofEmail')->once()->andReturn(\Mockery::mock(User::class));

        $this->expectException(UserAlreadyExistException::class);

        $this->command->execute(
            CreateUserCommandRequest::create(
                null,
                'prova@example.net'
            )
        );
    }
}
