<?php

declare(strict_types=1);

namespace TicketSystem\Ticket\Infrastructure\Domain\Doctrine;

use TicketSystem\Shared\Infrastructure\Doctrine\DoctrineEntityId;
use TicketSystem\Ticket\Domain\TicketId;

class DoctrineTicketId extends DoctrineEntityId
{
    protected function getFQCN(): string
    {
        return TicketId::class;
    }
}
