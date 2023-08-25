<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\CreateTicket;

final readonly class CreateTicketRequest
{
    private function __construct(
        public null|string $id,
        public string $title,
        public string $content,
        public string $priority,
        public string $category,
        public string $opener
    ) {
    }

    public static function create(
        null|string $id,
        string $title,
        string $content,
        string $priority,
        string $category,
        string $opener
    ): self {
        return new self($id, $title, $content, $priority, $category, $opener);
    }
}
