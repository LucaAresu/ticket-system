<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Doctrine;

use Doctrine\ORM\NonUniqueResultException;
use Ramsey\Uuid\Uuid;
use TicketSystem\Shared\Domain\Email;
use TicketSystem\Shared\Infrastructure\Doctrine\DoctrineRepository;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRepository;

/**
 * @template-extends DoctrineRepository<User, UserId>
 */
class DoctrineUserRepository extends DoctrineRepository implements UserRepository
{
    protected function getEntityClassName(): string
    {
        return User::class;
    }

    protected function getEntityAliasName(): string
    {
        return 'u';
    }

    public function nextId(): UserId
    {
        // todo potrebbe dare errore se genera un uuid già esistente
        return UserId::create(
            Uuid::uuid4()->toString()
        );
    }

    /**
     * @throws NonUniqueResultException
     */
    public function ofEmail(Email $email): null|User
    {
        /** @var null|User $user */
        $user = $this->createSelect()
            ->andWhere(sprintf('%s.email = :email', $this->getEntityAliasName()))
            ->setParameter('email', $email, \PDO::PARAM_STR)
            ->getQuery()
            ->getOneOrNullResult();

        return $user;
    }
}
