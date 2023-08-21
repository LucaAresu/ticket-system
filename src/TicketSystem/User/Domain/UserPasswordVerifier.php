<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

interface UserPasswordVerifier
{
    public function execute(string $passwordToVerify, string $hashedPassword): bool;
}
