<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\Shared\Domain;

use PHPUnit\Framework\TestCase;
use TicketSystem\Shared\Domain\Email;

class EmailTest extends TestCase
{
    /** @test */
    public function it_should_create_class(): void
    {
        $email = 'prova@example.net';

        $obj = Email::create($email);

        self::assertEquals($email, $obj->value);
    }

    /** @test */
    public function it_should_throw_exception(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $email = 'provaexample.net';

        Email::create($email);
    }

    /** @test */
    public function it_should_throw_exception_when_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $email = '';

        Email::create($email);
    }
}
