<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Doctrine;

use TicketSystem\Shared\Infrastructure\Doctrine\DoctrineEntityId;
use TicketSystem\User\Domain\UserId;

class DoctrineUserId extends DoctrineEntityId
{
    /**
     * @return class-string<UserId>
     */
    protected function getFQCN(): string
    {
        return UserId::class;
    }
}
