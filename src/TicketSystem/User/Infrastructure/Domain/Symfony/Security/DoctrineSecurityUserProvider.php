<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Symfony\Security;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DoctrineSecurityUserProvider implements UserProviderInterface, UserLoaderInterface
{
    public function __construct()
    {
    }

    public function refreshUser(UserInterface $user)
    {
        return new DoctrineSecurityUser($user->getUserIdentifier());
    }

    public function supportsClass(string $class)
    {
        return DoctrineSecurityUser::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return new DoctrineSecurityUser($identifier);
    }
}
