<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Domain;

final readonly class Email
{
    public string $value;

    private function __construct(string $email)
    {
        if (false === $this->isValid($email)) {
            throw new \InvalidArgumentException(sprintf('The email %s is not valid', $email));
        }

        $this->value = $email;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function create(string $email): self
    {
        return new self($email);
    }

    private function isValid(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
