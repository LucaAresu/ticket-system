<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\User\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Helpers\User\CreateUserTrait;
use Tests\Helpers\User\UserHelper;

class RetrieveAccessTokenTest extends WebTestCase
{
    use CreateUserTrait;
    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();

        $this->client->catchExceptions(false);
    }

    /** @test */
    public function it_should_return_200(): void
    {
        $this->createUser();

        $this->client->request('POST', 'v1/user/token', [
            'email' => UserHelper::email(),
            'password' => UserHelper::password(),
        ]);

        self::assertResponseIsSuccessful();
    }
}
