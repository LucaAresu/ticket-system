<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\User\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Helpers\User\Domain\CreateUserTrait;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\UserRepository;

class CreateUserTest extends WebTestCase
{
    use CreateUserTrait;

    /** @test */
    public function create_user_should_give_valid_response(): void
    {
        $client = self::createClient();

        $client->catchExceptions(false);

        $client->request('POST', '/v1/user', [
            'id' => '',
            'email' => 'prova2@example.net',
            'password' => 'fsafas',
        ]);

        self::assertResponseIsSuccessful();

        $container = self::getContainer();
        $repository = $container->get('TestUserRepository');

        /** @var UserRepository $user */
        $user = $repository->ofEmail(Email::create('prova2@example.net'));

        self::assertNotNull($user);
    }

    /** @test */
    public function it_should_error_when_empty_request(): void
    {
        $client = self::createClient();

        $client->catchExceptions(false);

        $client->request('POST', '/v1/user');

        self::assertResponseStatusCodeSame(401);
    }

    /** @test */
    public function logged_users_should_be_forbidden_to_create_users(): void
    {
        $client = self::createClient();
        $this->createAndLoginUser($client);

        $client->request('POST', '/v1/user', [
            'email' => 'some-email@example.net',
            'password' => 'some-password'
        ]);

        self::assertResponseStatusCodeSame(401);
    }
}
