<?php

declare(strict_types=1);

namespace Tests\Helpers\Ticket;

use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;
use TicketSystem\User\Domain\UserRole;

class TicketHelper
{
    public static function id(): string
    {
        return '2dc415af-1c4c-43e5-83b6-b4f4bd7e3e58';
    }

    public static function ticket(
        TicketPriority $priority = TicketPriority::LOW,
        TicketCategory $category = TicketCategory::IT,
        string $id = '2dc415af-1c4c-43e5-83b6-b4f4bd7e3e58',
        \DateTimeImmutable $createdAt = null
    ): Ticket {
        return Ticket::create(
            TicketId::create($id),
            'title',
            'content',
            $priority,
            $category,
            UserHelper::user()->become(UserRole::MANAGER),
            $createdAt
        );
    }
}
