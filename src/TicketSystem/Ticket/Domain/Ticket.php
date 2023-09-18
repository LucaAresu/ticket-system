<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use TicketSystem\Shared\Domain\Aggregate;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRole;

final class Ticket implements Aggregate
{
    private const TICKET_TITLE_MAX_LENGTH = 128;
    private const TICKET_CONTENT_MAX_LENGTH = 2048;
    public readonly \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $expiration;

    private TicketStatus $status;
    private null|UserId $operator;
    private \DateTimeImmutable $updatedAt;

    private function __construct(
        public readonly TicketId $id,
        public readonly string $title,
        public readonly string $content,
        private TicketPriority $priority,
        public readonly TicketCategory $category,
        public readonly UserId $opener,
        \DateTimeImmutable $createdAt = null,
    ) {
        $this->status = TicketStatus::WAITING_FOR_SUPPORT;
        $this->operator = null;
        $this->createdAt = $createdAt ?? new \DateTimeImmutable();
        $this->updatedAt = $createdAt ?? new \DateTimeImmutable();

        $this->calculateExpiration();
    }

    public static function create(
        TicketId $id,
        string $title,
        string $content,
        TicketPriority $priority,
        TicketCategory $category,
        User $opener,
        \DateTimeImmutable $createdAt = null,
    ): self {
        $instance = new self($id, $title, $content, $priority, $category, $opener->id, $createdAt);

        $instance->validateTitle($title);
        $instance->validateContent($content);
        $instance->validatePriority($priority, $opener);

        return $instance;
    }

    public function status(): TicketStatus
    {
        return $this->status;
    }

    public function priority(): TicketPriority
    {
        return $this->priority;
    }

    public function operator(): null|UserId
    {
        return $this->operator;
    }

    public function expiration(): \DateTimeImmutable
    {
        return $this->expiration;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isEqual(Ticket $ticket): bool
    {
        return $this->id->isEqual($ticket->id);
    }

    private function validateTitle(string $title): void
    {
        if ('' === $title) {
            throw new \InvalidArgumentException('The title must not be empty');
        }

        if (strlen($title) > self::TICKET_TITLE_MAX_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Ticket title length is too long. Max length is %d',
                    self::TICKET_TITLE_MAX_LENGTH
                )
            );
        }
    }

    private function validateContent(string $content): void
    {
        if ('' === $content) {
            throw new \InvalidArgumentException('The content must not be empty');
        }

        if (strlen($content) > self::TICKET_CONTENT_MAX_LENGTH) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Ticket content length is too long. Max length is %d',
                    self::TICKET_CONTENT_MAX_LENGTH
                )
            );
        }
    }

    private function validatePriority(TicketPriority $priority, User $user): void
    {
        if (TicketPriority::CRITICAL !== $priority) {
            return;
        }

        if (UserRole::MANAGER !== $user->role()) {
            throw WrongRoleForCriticalTicketException::create();
        }
    }

    private function calculateExpiration(): void
    {
        $this->expiration = $this->updatedAt->add(
            $this->priority->expirationIntervalBasedOnUrgency()
        );
    }
}
