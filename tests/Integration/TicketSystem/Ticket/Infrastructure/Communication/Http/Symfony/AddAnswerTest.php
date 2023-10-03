<?php

declare(strict_types=1);

namespace Integration\TicketSystem\Ticket\Infrastructure\Communication\Http\Symfony;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tests\Helpers\User\CreateUserTrait;
use TicketSystem\Ticket\Domain\TicketCategory;

class AddAnswerTest extends WebTestCase
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
    public function it_should_add_answer(): void
    {
        $ticketId = $this->createTicket();

        $this->client->getHistory()->clear();

        $this->client->request(
            'POST',
            sprintf('v1/ticket/%s/answer', $ticketId),
            [
                'content' => 'answer text',
            ]
        );
        self::assertResponseIsSuccessful();

        $response = json_decode($this->client->getResponse()->getContent(), true);

        self::assertNotEmpty($response['answers']);
        self::assertEquals('answer text', $response['answers'][0]['content']);
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
