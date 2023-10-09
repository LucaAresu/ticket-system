<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\Answer;

readonly class AnswerDto
{
    private function __construct(
        public string $id,
        public string $user,
        public string $content,
        public \DateTimeImmutable $createdAt
    ) {
    }

    public static function createFrom(Answer $answer): self
    {
        return new self(
            $answer->id->id,
            $answer->userId->id,
            $answer->content,
            $answer->createdAt
        );
    }
}
