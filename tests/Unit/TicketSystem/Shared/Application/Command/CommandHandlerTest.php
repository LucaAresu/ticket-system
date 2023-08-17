<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Shared\Application\Command;

use Monolog\Test\TestCase;
use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\Shared\Application\Command\CommandFailureResponse;
use TicketSystem\Shared\Application\Command\CommandHandler;
use TicketSystem\Shared\Application\Logger;
use TicketSystem\Shared\Domain\DomainException;

class CommandHandlerTest extends TestCase
{
    private CommandHandler $commandHandler;
    private Command $command;
    private Logger $logger;

    public function setUp(): void
    {
        $this->logger = \Mockery::mock(Logger::class);

        $this->command = \Mockery::mock(Command::class);

        $this->commandHandler = new CommandHandler($this->command, $this->logger);
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /** @test */
    public function it_should_give_the_expected_response(): void
    {
        $expected = 'resp';
        $this->command->shouldReceive('execute')->once()->andReturn($expected);

        $response = $this->commandHandler->execute('req');

        self::assertEquals($expected, $response);
    }

    /** @test */
    public function it_should_give_401_status_on_invalid_argument_exception(): void
    {
        $exceptionMessage = 'not valid';
        $this->command->shouldReceive('execute')->andReturnUsing(static function () use ($exceptionMessage) {
            throw new \InvalidArgumentException($exceptionMessage);
        });

        $response = $this->commandHandler->execute('req');

        self::assertInstanceOf(CommandFailureResponse::class, $response);
        self::assertStringContainsString($exceptionMessage, $response->message);
        self::assertSame(401, $response->status);
    }

    /** @test */
    public function it_should_give_500_status_on_domain_exception(): void
    {
        $e = new DomainException();

        $this->command->shouldReceive('execute')->andReturnUsing(static function () use ($e) {
            throw $e;
        });
        $this->logger->shouldReceive('error')->once()->with($e->getMessage(), [$e]);

        $response = $this->commandHandler->execute('req');

        self::assertInstanceOf(CommandFailureResponse::class, $response);
        self::assertSame(500, $response->status);
    }

    /** @test */
    public function it_should_give_500_status_on_exception(): void
    {
        $e = new \Exception();

        $this->command->shouldReceive('execute')->andReturnUsing(static function () use ($e) {
            throw $e;
        });
        $this->logger->shouldReceive('critical')->once()->with($e->getMessage(), [$e]);

        $response = $this->commandHandler->execute('req');

        self::assertInstanceOf(CommandFailureResponse::class, $response);
        self::assertSame(500, $response->status);
    }
}
