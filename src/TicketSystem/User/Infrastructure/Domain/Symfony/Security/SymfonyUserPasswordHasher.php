<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Symfony\Security;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use TicketSystem\User\Domain\UserPasswordHasher;

class SymfonyUserPasswordHasher extends UserPasswordHasher
{
    private PasswordHasherInterface $passwordHasher;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasher = $passwordHasherFactory->getPasswordHasher(DoctrineSecurityUser::class);
    }

    protected function hash(string $password): string
    {
        return $this->passwordHasher->hash($password);
    }
}
