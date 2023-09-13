<?php

declare(strict_types=1);

namespace Integration\TicketSystem\User\Infrastructure\Communication\Cli\Symfony\Command;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Domain\UserRole;
use TicketSystem\User\Infrastructure\Communication\Cli\Symfony\Command\SymfonyCommandInitUsers;

class InitUsersTest extends KernelTestCase
{
    private SymfonyCommandInitUsers $command;
    private UserRepository $userRepository;

    public function setUp(): void
    {
        self::bootKernel();
        $this->command = self::getContainer()->get('TestInitUsers');
        $this->userRepository = self::getContainer()->get('TestUserRepository');
    }

    /** @test */
    public function it_should_create_an_user(): void
    {
        $res = $this->command->execute();

        $user = $this->userRepository->ofEmail(Email::create('user@example.net'));

        self::assertEquals(Command::SUCCESS, $res);
        self::assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function it_should_create_a_manager(): void
    {
        $res = $this->command->execute();

        $user = $this->userRepository->ofEmail(Email::create('manager@example.net'));

        self::assertEquals(Command::SUCCESS, $res);
        self::assertEquals(UserRole::MANAGER, $user->role());
    }

    /** @test */
    public function it_should_create_an_operator(): void
    {
        $res = $this->command->execute();

        $user = $this->userRepository->ofEmail(Email::create('operator@example.net'));

        self::assertEquals(Command::SUCCESS, $res);
        self::assertNotNull($user->operatorCategory());
    }

    /** @test */
    public function it_should_create_a_super_operator(): void
    {
        $res = $this->command->execute();

        $user = $this->userRepository->ofEmail(Email::create('super-operator@example.net'));

        self::assertEquals(Command::SUCCESS, $res);
        self::assertTrue($user->isSuperOperator());
        self::assertNull($user->operatorCategory());
    }
}
