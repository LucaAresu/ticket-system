<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\User\Application\CreateUser\CreateUserCommand;
use TicketSystem\User\Application\CreateUser\CreateUserCommandRequest;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Domain\UserRole;
use Webmozart\Assert\Assert;

class InitUsers extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private CreateUserCommand $createUserCommand
    ) {
        parent::__construct('app:init:users');
    }

    public function execute(InputInterface $input = null, OutputInterface $output = null): int
    {
        $this->executeCommand();

        return Command::SUCCESS;
    }

    public function executeCommand(): void
    {
        $this->createUser();
        $this->createManager();
        $this->createOperator();
        $this->createSuperOperator();
    }

    private function createUser(): void
    {
        $this->createUserCommand->execute(
            CreateUserCommandRequest::create(
                '9fa02cc4-06df-42b1-9163-7d9c44774f46',
                'user@example.net',
                'User',
                'User',
                'password',
            )
        );
    }

    private function createManager(): void
    {
        $email = 'manager@example.net';

        $this->createUserCommand->execute(
            CreateUserCommandRequest::create(
                '265765b4-4918-4871-af80-b33682139ef4',
                $email,
                'Manager',
                'Manager',
                'password',
            )
        );

        $user = $this->userRepository->ofEmail(Email::create($email));

        Assert::isInstanceOf($user, User::class);

        $user->become(UserRole::MANAGER);

        $this->userRepository->save($user);
    }

    /** @test */
    private function createOperator(): void
    {
        $email = 'operator@example.net';

        $this->createUserCommand->execute(
            CreateUserCommandRequest::create(
                'e1cfa3a8-e244-4df8-bc54-e338105b40e3',
                $email,
                'Operator',
                'Operator',
                'password',
            )
        );

        $user = $this->userRepository->ofEmail(Email::create($email));

        Assert::isInstanceOf($user, User::class);

        $user->become(UserRole::OPERATOR, TicketCategory::MARKETING);

        $this->userRepository->save($user);
    }

    private function createSuperOperator(): void
    {
        $email = 'super-operator@example.net';

        $this->createUserCommand->execute(
            CreateUserCommandRequest::create(
                'bad08387-f837-48b0-bc49-900d6a1014ff',
                $email,
                'SuperOperator',
                'SuperOperator',
                'password',
            )
        );

        $user = $this->userRepository->ofEmail(Email::create($email));

        Assert::isInstanceOf($user, User::class);

        $user->become(UserRole::SUPER_OPERATOR);

        $this->userRepository->save($user);
    }
}
