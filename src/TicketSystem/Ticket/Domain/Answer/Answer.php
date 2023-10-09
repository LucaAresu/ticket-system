<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\Answer;

use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\User\Domain\UserId;

class Answer
{
    private function __construct(
        public readonly AnswerId $id,
        private readonly Ticket $ticket,
        public readonly UserId $userId,
        public readonly string $content,
        public readonly \DateTimeImmutable $createdAt
    ) {
        if ('' === $this->content) {
            throw EmptyAnswerException::create();
        }
    }

    public static function create(
        AnswerId $id,
        Ticket $ticket,
        UserId $userId,
        string $content,
        \DateTimeImmutable $createdAt = null
    ): self {
        return new self(
            $id,
            $ticket,
            $userId,
            $content,
            $createdAt ?? new \DateTimeImmutable()
        );
    }
}
