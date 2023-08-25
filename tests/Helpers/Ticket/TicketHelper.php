<?php

declare(strict_types=1);

namespace Tests\Helpers\Ticket;

use Tests\Helpers\User\UserHelper;
use TicketSystem\Ticket\Domain\Ticket;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\Ticket\Domain\TicketId;
use TicketSystem\Ticket\Domain\TicketPriority;

class TicketHelper
{
    public static function id(): string
    {
        return '2dc415af-1c4c-43e5-83b6-b4f4bd7e3e58';
    }

    public static function ticket(
        TicketPriority $priority = TicketPriority::LOW,
        TicketCategory $category = TicketCategory::IT
    ): Ticket {
        return Ticket::create(
            TicketId::create(self::id()),
            'title',
            'content',
            $priority,
            $category,
            UserHelper::user()
        );
    }
}
