<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Domain\CreateTicket;

use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketDto;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;
use TicketSystem\Ticket\Domain\TicketRepository;
use TicketSystem\Ticket\Domain\TooManyTicketCreated;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserNotFoundException;
use TicketSystem\User\Domain\UserRepository;
use TicketSystem\User\Domain\UserRole;

class CreateTicket
{
    public function __construct(
        private TicketRepository $ticketRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function execute(CreateTicketRequest $request): TicketDto
    {
        $ticketOpener = $this->getUserOrFail($request->opener);

        $ticketCategory = $this->getEnumValueOrFail(TicketCategory::class, $request->category);

        if (false === $this->canUserOpenATicketInCategory($ticketOpener, $ticketCategory)) {
            throw TooManyTicketCreated::create($ticketCategory);
        }

        $ticket = Ticket::create(
            $request->id ? TicketId::create($request->id) : $this->ticketRepository->nextId(),
            $request->title,
            $request->content,
            $this->getEnumValueOrFail(TicketPriority::class, $request->priority),
            $ticketCategory,
            $ticketOpener
        );

        $this->ticketRepository->save($ticket);

        return TicketDto::createFrom($ticket);
    }

    private function getUserOrFail(string $userId): User
    {
        $user = $this->userRepository->ofId(UserId::create($userId));

        if (null === $user) {
            throw UserNotFoundException::create($userId);
        }

        return $user;
    }

    private function canUserOpenATicketInCategory(User $ticketOpener, TicketCategory $ticketCategory): bool
    {
        return 0 === $this->ticketRepository->getOpenTicketsCountForUserInCategory($ticketOpener->id, $ticketCategory)
        || UserRole::MANAGER === $ticketOpener->role();
    }

    /**
     * @template T of \BackedEnum
     *
     * @param class-string<T> $enum
     *
     * @return T
     */
    private function getEnumValueOrFail(string $enum, string $value): \BackedEnum
    {
        try {
            return $enum::from($value);
        } catch (\Throwable $e) {
            throw new \InvalidArgumentException(sprintf('%s is not a valid value for %s', $value, $enum));
        }
    }
}
