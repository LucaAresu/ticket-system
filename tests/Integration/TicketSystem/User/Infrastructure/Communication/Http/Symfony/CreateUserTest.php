<?php

declare(strict_types=1);

namespace Integration\TicketSystem\User\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\UserRepository;

class CreateUserTest extends WebTestCase
{
    /** @test */
    public function create_user_should_give_valid_response(): void
    {
        $client = self::createClient();

        $client->catchExceptions(false);

        $client->request('POST', '/v1/user', [
            'id' => '',
            'email' => 'prova2@example.net',
        ]);

        self::assertResponseIsSuccessful();

        $container = self::getContainer();
        $repository = $container->get('TestUserRepository');

        /** @var UserRepository $user */
        $user = $repository->ofEmail(Email::create('prova2@example.net'));

        self::assertNotNull($user);
    }
}
