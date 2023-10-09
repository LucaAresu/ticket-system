<?php

declare(strict_types=1);

namespace Tests\Unit\TicketSystem\User\Domain;

use PHPUnit\Framework\TestCase;
use TicketSystem\User\Domain\UserPasswordHasher;

class UserPasswordHasherTest extends TestCase
{
    private UserPasswordHasher $userPasswordHasher;

    protected function setUp(): void
    {
        $this->userPasswordHasher = new class() extends UserPasswordHasher {
            protected function hash(string $password): string
            {
                return 'it works';
            }
        };
    }

    /** @test */
    public function it_should_work_if_password_given(): void
    {
        $result = $this->userPasswordHasher->execute('password');

        self::assertEquals('it works', $result);
    }

    /** @test */
    public function it_should_error_if_password_empty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->userPasswordHasher->execute('');
    }
}
