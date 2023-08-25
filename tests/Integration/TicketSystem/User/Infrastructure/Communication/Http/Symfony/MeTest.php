<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\User\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Helpers\User\CreateUserTrait;
use Tests\Helpers\User\UserHelper;

class MeTest extends WebTestCase
{
    use CreateUserTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
    }

    /** @test */
    public function it_should_work(): void
    {
        $this->createAndLoginUser($this->client);

        $this->client->request('GET', '/me');

        self::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertEquals(UserHelper::email(), $response['email']);
    }

    /** @test */
    public function it_should_401_if_not_logged(): void
    {
        $this->client->request('GET', '/me');

        self::assertResponseStatusCodeSame(401);
    }
}
