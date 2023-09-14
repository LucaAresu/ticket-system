<?php

declare(strict_types=1);

namespace TicketSystem\User\Infrastructure\Domain\Doctrine;

use TicketSystem\Shared\Infrastructure\Doctrine\DoctrineEntityId;
use TicketSystem\User\Domain\Operator\OperatorId;

class DoctrineOperatorId extends DoctrineEntityId
{
    /**
     * @return class-string<OperatorId>
     */
    protected function getFQCN(): string
    {
        return OperatorId::class;
    }
}
