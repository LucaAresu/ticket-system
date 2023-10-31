<?php

declare(strict_types=1);

namespace Integration\TicketSystem\Ticket\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Helpers\User\CreateUserTrait;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketStatus;

class CloseTicketTest extends WebTestCase
{
    use CreateUserTrait;

    public function setUp(): void
    {
        $this->client = self::createClient();
        $this->client->catchExceptions(false);

        $this->createAndLoginOperator($this->client, TicketCategory::HR);
    }

    /** @test */
    public function it_should_be_closed(): void
    {
        $ticketId = $this->createTicket();

        $this->client->getHistory()->clear();

        $this->client->request(
            'POST',
            sprintf('v1/ticket/%s/close', $ticketId),
        );
        self::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertEquals(TicketStatus::CLOSED->value, $response['status']);
    }

    private function createTicket(): string
    {
        $this->client->request('POST', 'v1/ticket', [
            'title' => 'sfafsfsa',
            'content' => 'fasfasfasfs',
            'priority' => 'LOW',
            'category' => 'IT',
        ]);

        self::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        return $response['id'];
    }
}
