<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Communication\Cli\Symfony\Command;

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

class SymfonyCommandInitUsers extends Command
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
                null,
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
                null,
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
                null,
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
                null,
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
