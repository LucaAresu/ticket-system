<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Symfony\Security;

use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use TicketSystem\User\Domain\UserPasswordVerifier;

class SymfonyUserPasswordVerifier implements UserPasswordVerifier
{
    private PasswordHasherInterface $passwordHasher;

    public function __construct(PasswordHasherFactoryInterface $passwordHasherFactory)
    {
        $this->passwordHasher = $passwordHasherFactory->getPasswordHasher(DoctrineSecurityUser::class);
    }

    public function execute(string $passwordToVerify, string $hashedPassword): bool
    {
        return $this->passwordHasher->verify($hashedPassword, $passwordToVerify);
    }
}
