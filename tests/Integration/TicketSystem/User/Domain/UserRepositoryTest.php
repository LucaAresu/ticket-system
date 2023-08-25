<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\User\Domain;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Helpers\User\Domain\UserHelper;
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

        $user = UserHelper::user();

        $repository->save($user);

        $user2 = $repository->ofId(UserId::create(UserHelper::userId()));

        self::assertTrue($user->isEqual($user2));
    }
}
