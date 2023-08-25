<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

final readonly class TicketDto
{
    private function __construct(
        public string $id,
        public string $title,
        public string $content,
        public TicketStatus $status,
        public TicketPriority $priority,
        public TicketCategory $category,
        public string $opener,
        public null|string $operator,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt
    ) {
    }

    public static function createFrom(Ticket $ticket): self
    {
        return new self(
            $ticket->id->id,
            $ticket->title,
            $ticket->content,
            $ticket->status(),
            $ticket->priority(),
            $ticket->category,
            $ticket->opener->id,
            $ticket->operator()?->id,
            $ticket->createdAt,
            $ticket->updatedAt()
        );
    }
}
