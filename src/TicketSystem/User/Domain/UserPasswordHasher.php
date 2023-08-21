<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

interface UserPasswordHasher
{
    public function execute(string $password): string;
}
