<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use TicketSystem\Shared\Domain\Aggregate;
use TicketSystem\Ticket\Domain\Answer\Answer;
use TicketSystem\Ticket\Domain\Answer\AnswerDto;
use TicketSystem\Ticket\Domain\Answer\AnswerId;
use TicketSystem\Ticket\Domain\Answer\ForbiddenAnswerException;
use TicketSystem\User\Domain\Operator\OperatorId;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRole;

final class Ticket implements Aggregate
{
    private const TICKET_TITLE_MAX_LENGTH = 128;
    private const TICKET_CONTENT_MAX_LENGTH = 2048;
    public readonly \DateTimeImmutable $createdAt;
    private null|\DateTimeImmutable $expiration;

    private TicketStatus $status;
    private null|OperatorId $operator;
    private \DateTimeImmutable $updatedAt;

    /**
     * @var ArrayCollection<int, Answer>|Collection<int, Answer>
     */
    private Collection|ArrayCollection $answers;

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

        $this->answers = new ArrayCollection();

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

    public function operator(): null|OperatorId
    {
        return $this->operator;
    }

    public function expiration(): null|\DateTimeImmutable
    {
        return $this->expiration;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function isEqual(null|Ticket $ticket): bool
    {
        if (null === $ticket) {
            return false;
        }

        return $this->id->isEqual($ticket->id);
    }

    public function assignTo(OperatorId $operator): self
    {
        $this->operator = $operator;

        $this->ticketUpdated();

        return $this;
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
        if (TicketStatus::WAITING_FOR_SUPPORT === $this->status) {
            $this->expiration = $this->updatedAt->add(
                $this->priority->expirationIntervalBasedOnUrgency()
            );

            return;
        }

        $this->expiration = null;
    }

    private function ticketUpdated(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function addAnswer(AnswerId $answerId, User $user, string $content): self
    {
        if (false === $this->canAnswer($user)) {
            throw ForbiddenAnswerException::create($user->id, $this->id);
        }
        $this->createAnswer($answerId, $user, $content);

        $this->updateStatusByAnswerer($user);

        $this->ticketUpdated();
        $this->calculateExpiration();

        return $this;
    }

    private function canAnswer(User $user): bool
    {
        if ($user->id->isEqual($this->opener)) {
            return true;
        }

        return $user->operatorId()->isEqual($this->operator)
            || $user->isSuperOperator();
    }

    private function createAnswer(AnswerId $answerId, User $user, string $content): void
    {
        $this->answers->add(
            Answer::create(
                $answerId,
                $this,
                $user->id,
                $content,
            )
        );
    }

    private function updateStatusByAnswerer(User $user): void
    {
        $this->status = match (true) {
            $user->id->isEqual($this->opener) => TicketStatus::WAITING_FOR_SUPPORT,
            $user->isOperator() => TicketStatus::WAITING_FOR_USER,
            default => throw new \LogicException('Ticket status can\'t be assigned')
        };
    }

    /**
     * @return array<int, AnswerDto>
     */
    public function answers(): array
    {
        $answers = [];

        foreach ($this->answers->getValues() as $answer) {
            $answers[] = AnswerDto::createFrom($answer);
        }

        return $answers;
    }
}
