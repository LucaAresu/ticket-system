<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Application\AddAnswer;

class AddAnswerRequest
{
    private function __construct(
        public null|string $userId,
        public null|string $ticketId,
        public null|string $content
    ) {
    }

    public static function create(
        null|string $userId,
        null|string $ticketId,
        null|string $content
    ): self {
        return new self($userId, $ticketId, $content);
    }
}
