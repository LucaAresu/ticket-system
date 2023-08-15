<?php

declare(strict_types=1);

namespace Integration\TicketSystem\User\Domain;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;

class UserRepositoryTest extends KernelTestCase
{
    /** @test */
    public function it_should_save_an_user(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        /** @var UserRepository $repository */
        $repository = $container->get('TestUserRepository');

        $id = UserId::create('2dc415af-1c4c-43e5-83b6-b4f4bd7e3e58');
        $user = User::create(
            $id,
            Email::create('prova@example.net')
        );

        $repository->save($user);

        $user2 = $repository->ofId($id);

        self::assertTrue($user->isEqual($user2));
    }
}
