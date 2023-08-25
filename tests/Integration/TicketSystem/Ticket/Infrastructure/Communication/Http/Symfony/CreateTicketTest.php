<?php

declare(strict_types=1);

namespace Integration\TicketSystem\Ticket\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\CreateUserTrait;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketRepository;

class CreateTicketTest extends WebTestCase
{
    use CreateUserTrait;

    /** @test */
    public function it_should_create_a_ticket(): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);

        $this->createAndLoginUser($client);

        /** @var TicketRepository $ticketRepository */
        $ticketRepository = self::getContainer()->get('TestTicketRepository');

        $client->request('POST', 'v1/ticket', [
            'title' => 'sfafsfsa',
            'content' => 'fasfasfasfs',
            'priority' => 'LOW',
            'category' => 'IT',
        ]);

        self::assertResponseIsSuccessful();

        $response = json_decode($client->getResponse()->getContent(), true);

        $ticket = $ticketRepository->ofId(TicketId::create($response['id']));

        self::assertNotNull($ticket);
    }

    /** @test */
    public function it_should_401_if_not_logged(): void
    {
        $client = self::createClient();

        $client->request('POST', 'v1/ticket', [
            'title' => 'sfafsfsa',
            'content' => 'fasfasfasfs',
            'priority' => 'LOW',
            'category' => 'IT',
        ]);

        self::assertResponseStatusCodeSame(401);
    }

    /** @test */
    public function it_should_create_a_ticket_with_uuid_given(): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);

        $this->createAndLoginUser($client);

        /** @var TicketRepository $ticketRepository */
        $ticketRepository = self::getContainer()->get('TestTicketRepository');

        $client->request('POST', 'v1/ticket', [
            'id' => TicketHelper::id(),
            'title' => 'sfafsfsa',
            'content' => 'fasfasfasfs',
            'priority' => 'LOW',
            'category' => 'IT',
        ]);

        self::assertResponseIsSuccessful();

        json_decode($client->getResponse()->getContent(), true);

        $ticket = $ticketRepository->ofId(TicketId::create(TicketHelper::id()));

        self::assertNotNull($ticket);
    }

    /** @test */
    public function it_should_error_when_you_create_two_tickets(): void
    {
        $client = self::createClient();
        $client->catchExceptions(false);

        $this->createAndLoginUser($client);

        $client->request('POST', 'v1/ticket', [
            'title' => 'sfafsfsa',
            'content' => 'fasfasfasfs',
            'priority' => 'LOW',
            'category' => 'IT',
        ]);

        $client->getHistory()->clear();

        $client->request('POST', 'v1/ticket', [
            'title' => 'sfafsfsa',
            'content' => 'fasfasfasfs',
            'priority' => 'LOW',
            'category' => 'IT',
        ]);

        self::assertResponseStatusCodeSame(500);
        self::assertStringContainsString(
            'have already created a ticket in category IT',
            $client->getResponse()->getContent()
        );
    }
}
