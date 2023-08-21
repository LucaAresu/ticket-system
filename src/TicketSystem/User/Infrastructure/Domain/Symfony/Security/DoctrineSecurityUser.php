<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Symfony\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class DoctrineSecurityUser implements UserInterface
{
    public function __construct(private readonly string $identifier)
    {
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    public function eraseCredentials(): void
    {
    }
}
