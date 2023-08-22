<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

abstract class UserPasswordHasher
{
    public function execute(string $password): string
    {
        if ('' === $password) {
            throw new \InvalidArgumentException('The password must not be empty');
        }

        return $this->hash($password);
    }

    abstract protected function hash(string $password): string;
}
