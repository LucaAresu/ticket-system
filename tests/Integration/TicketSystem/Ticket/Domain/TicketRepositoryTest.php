<?php

declare(strict_types=1);

namespace Tests\Integration\TicketSystem\Ticket\Domain;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Tests\Helpers\Ticket\TicketHelper;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\Ticket\Domain\TicketStatus;

class TicketRepositoryTest extends KernelTestCase
{
    /** @test */
    public function it_should_save_a_ticket(): void
    {
        self::bootKernel();
        $container = self::$kernel->getContainer();

        /** @var TicketRepository $repository */
        $repository = $container->get('TestTicketRepository');

        $ticket = TicketHelper::ticket(TicketPriority::LOW);

        $repository->save($ticket);

        $ticket2 = $repository->ofId(TicketId::create(TicketHelper::id()));

        self::assertTrue($ticket->isEqual($ticket2));
        self::assertEquals(TicketPriority::LOW, $ticket->priority());
        self::assertEquals(TicketStatus::WAITING_FOR_SUPPORT, $ticket->status());
        self::assertNotNull($ticket->createdAt);
    }
}
