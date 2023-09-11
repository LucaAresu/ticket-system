<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Domain;

use Monolog\Test\TestCase;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRole;

class UserTest extends TestCase
{
    /** @test */
    public function it_should_error_when_name_is_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        User::create(
            UserId::create(UserHelper::userId()),
            Email::create(UserHelper::email()),
            '',
            'Something',
            'asda',
            UserRole::USER
        );
    }

    /** @test */
    public function it_should_error_when_lastname_is_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        User::create(
            UserId::create(UserHelper::userId()),
            Email::create(UserHelper::email()),
            'fsafas',
            '',
            'asda',
            UserRole::USER
        );
    }

    /** @test */
    public function it_should_error_when_name_is_65(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $string = str_repeat('a', 65);
        User::create(
            UserId::create(UserHelper::userId()),
            Email::create(UserHelper::email()),
            $string,
            'fsafas',
            'asda',
            UserRole::USER
        );
    }

    /** @test */
    public function it_should_work_when_name_is_64(): void
    {
        $string = str_repeat('a', 64);

        $user = User::create(
            UserId::create(UserHelper::userId()),
            Email::create(UserHelper::email()),
            $string,
            'fsafas',
            'asda',
            UserRole::USER
        );

        self::assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function it_should_error_when_lastname_is_65(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $string = str_repeat('a', 65);
        User::create(
            UserId::create(UserHelper::userId()),
            Email::create(UserHelper::email()),
            'fsafas',
            $string,
            'asda',
            UserRole::USER
        );
    }

    /** @test */
    public function it_should_work_when_lastname_is_64(): void
    {
        $string = str_repeat('a', 64);

        $user = User::create(
            UserId::create(UserHelper::userId()),
            Email::create(UserHelper::email()),
            'fsafas',
            $string,
            'asda',
            UserRole::USER
        );

        self::assertInstanceOf(User::class, $user);
    }
}
