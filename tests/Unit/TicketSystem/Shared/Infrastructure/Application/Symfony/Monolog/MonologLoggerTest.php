<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Shared\Infrastructure\Application\Symfony\Monolog;

use Monolog\Test\TestCase;
use Psr\Log\LoggerInterface;
use TicketSystem\Shared\Infrastructure\Application\Symfony\Monolog\MonologLogger;

class MonologLoggerTest extends TestCase
{
    private LoggerInterface $logger;
    private MonologLogger $monologLogger;

    protected function setUp(): void
    {
        parent::setUp();

        $this->logger = \Mockery::mock(LoggerInterface::class);
        $this->monologLogger = new MonologLogger($this->logger);
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function it_should_log_emergency_message()
    {
        $message = 'Emergency message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('emergency')
            ->once()
            ->with($message, $context);

        $this->monologLogger->emergency($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_alert_message()
    {
        $message = 'Alert message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('alert')
            ->once()
            ->with($message, $context);

        $this->monologLogger->alert($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_critical_message()
    {
        $message = 'Critical message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('critical')
            ->once()
            ->with($message, $context);

        $this->monologLogger->critical($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_error_message()
    {
        $message = 'Error message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('error')
            ->once()
            ->with($message, $context);

        $this->monologLogger->error($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_warning_message()
    {
        $message = 'Warning message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('warning')
            ->once()
            ->with($message, $context);

        $this->monologLogger->warning($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_notice_message()
    {
        $message = 'Notice message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('notice')
            ->once()
            ->with($message, $context);

        $this->monologLogger->notice($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_info_message()
    {
        $message = 'Info message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('info')
            ->once()
            ->with($message, $context);

        $this->monologLogger->info($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_debug_message()
    {
        $message = 'Debug message';
        $context = ['key' => 'value'];

        $this->logger
            ->shouldReceive('debug')
            ->once()
            ->with($message, $context);

        $this->monologLogger->debug($message, $context);
        self::assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_log_message_at_specified_level()
    {
        $message = 'Log message';
        $context = ['key' => 'value'];
        $level = 1;

        $this->logger
            ->shouldReceive('log')
            ->once()
            ->with($level, $message, $context);

        $this->monologLogger->log($level, $message, $context);

        self::assertTrue(true);
    }
}
