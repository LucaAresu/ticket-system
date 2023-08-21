<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Email;

interface UserRepository
{
    public function nextId(): UserId;

    public function ofId(UserId $id): null|User;

    public function ofEmail(Email $email): null|User;

    public function save(User $user): void;
}
