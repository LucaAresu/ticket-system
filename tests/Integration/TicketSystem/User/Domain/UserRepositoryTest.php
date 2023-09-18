<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\User\Domain;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Domain\UserRole;

class UserRepositoryTest extends KernelTestCase
{
    private UserRepository $userRepository;

    public function setUp(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        $this->userRepository = $container->get('TestUserRepository');
    }

    /** @test */
    public function it_should_save_an_user(): void
    {
        $user = UserHelper::user();

        $this->userRepository->save($user);

        $user2 = $this->userRepository->ofId(UserId::create(UserHelper::userId()));

        self::assertTrue($user->isEqual($user2));
    }

    /** @test */
    public function it_should_become_operator(): void
    {
        $user = UserHelper::user();
        $user->become(UserRole::OPERATOR, TicketCategory::MARKETING);

        $this->userRepository->save($user);

        $user2 = $this->userRepository->ofId(UserId::create(UserHelper::userId()));

        self::assertEquals(TicketCategory::MARKETING, $user2->operatorCategory());
    }
}
