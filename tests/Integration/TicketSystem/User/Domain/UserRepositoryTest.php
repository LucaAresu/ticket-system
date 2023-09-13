<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\User\Domain;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\TicketCategory;
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

    public function prova_qualcosa_boh(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        /** @var UserRepository $repository */
        $repository = $container->get('TestUserRepository');

        $a = $repository->ofId(UserId::create('98109a2c-50f1-4ee2-b88a-70385dddb62b'));

        $a->bla();

        $repository->save($a);

        $b = $repository->ofId(UserId::create('98109a2c-50f1-4ee2-b88a-70385dddb62b'));

        self::assertEquals(TicketCategory::MARKETING, $b->operator->assignedCategory());
        $this->fail('test da completare');
    }
}
