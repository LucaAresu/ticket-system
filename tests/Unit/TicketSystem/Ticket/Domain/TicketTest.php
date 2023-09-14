<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Ticket\Domain;

use Monolog\Test\TestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;

class TicketTest extends TestCase
{
    /** @test */
    public function it_should_give_exception_if_title_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Ticket::create(
            TicketId::create(TicketHelper::id()),
            '',
            'msaiofam',
            TicketPriority::LOW,
            TicketCategory::HR,
            UserHelper::user()
        );
    }

    /** @test */
    public function it_should_give_error_if_content_is_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Ticket::create(
            TicketId::create(TicketHelper::id()),
            'fsafsa',
            '',
            TicketPriority::LOW,
            TicketCategory::HR,
            UserHelper::user()
        );
    }

    /** @test */
    public function it_should_give_error_if_title_lenght_over_128(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $string = str_repeat('a', 129);

        Ticket::create(
            TicketId::create(TicketHelper::id()),
            $string,
            'fsafsafsa',
            TicketPriority::LOW,
            TicketCategory::HR,
            UserHelper::user()
        );
    }

    /** @test */
    public function it_should_create_if_title_lenght_is_128(): void
    {
        $string = str_repeat('a', 128);

        Ticket::create(
            TicketId::create(TicketHelper::id()),
            $string,
            'fsafsafsa',
            TicketPriority::LOW,
            TicketCategory::HR,
            UserHelper::user()
        );

        self::assertTrue(true);
    }

    /** @test */
    public function it_should_give_error_if_content_lenght_over_2048(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $string = str_repeat('a', 2049);

        Ticket::create(
            TicketId::create(TicketHelper::id()),
            'fsafsafsa',
            $string,
            TicketPriority::LOW,
            TicketCategory::HR,
            UserHelper::user()
        );
    }

    /** @test */
    public function it_should_create_if_content_lenght_is_2048(): void
    {
        $string = str_repeat('a', 2048);

        Ticket::create(
            TicketId::create(TicketHelper::id()),
            'fsafsafsa',
            $string,
            TicketPriority::LOW,
            TicketCategory::HR,
            UserHelper::user()
        );

        self::assertTrue(true);
    }
}
