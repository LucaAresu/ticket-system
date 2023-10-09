<?php

declare(strict_types=1);

namespace Integration\TicketSystem\Ticket\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Helpers\Ticket\TicketHelper;
use Tests\Helpers\User\CreateUserTrait;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\User\Domain\Operator\OperatorId;

class NextTicketTest extends WebTestCase
{
    use CreateUserTrait;

    private KernelBrowser $client;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->catchExceptions(false);

        $this->createAndLoginOperator($this->client, TicketCategory::HR);
    }

    /** @test */
    public function it_should_return_a_ticket_with_operator_assigned(): void
    {
        $ticket = TicketHelper::ticket(category: TicketCategory::HR);

        /** @var TicketRepository $ticketRepository */
        $ticketRepository = self::getContainer()->get('TestTicketRepository');
        $ticketRepository->save($ticket);

        $this->client->request('GET', 'v1/ticket/next');

        self::assertResponseIsSuccessful();

        $ticketId = json_decode($this->client->getResponse()->getContent(), true)['id'] ?? null;

        self::assertNotNull($ticketId);

        $loadedTicket = $ticketRepository->ofId(TicketId::create($ticketId));

        self::assertInstanceOf(Ticket::class, $loadedTicket);

        self::assertNull($ticket->operator());
        self::assertInstanceOf(OperatorId::class, $loadedTicket->operator());
    }

    /** @test */
    public function it_should_return_no_ticket_available_message(): void
    {
        $this->client->request('GET', 'v1/ticket/next');

        self::assertResponseIsSuccessful();

        $response = $this->client->getResponse()->getContent();

        self::assertStringContainsString('No ticket available', $response);
    }
}
