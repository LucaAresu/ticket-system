<?php

declare(strict_types=1);

namespace Tests\Helpers\User;

use TicketSystem\Shared\Domain\Email;
use TicketSystem\Ticket\Domain\TicketCategory;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;
use TicketSystem\User\Domain\UserRole;

class UserHelper
{
    public static function userId(): string
    {
        return '2dc415af-1c4c-43e5-83b6-b4f4bd7e3e58';
    }

    public static function email(): string
    {
        return 'prova@example.net';
    }

    public static function password(): string
    {
        return 'asd123';
    }

    public static function user(UserRole $role = UserRole::USER, string $id = null, TicketCategory $category = TicketCategory::HR): User
    {
        $user = User::create(
            UserId::create($id ?? self::userId()),
            Email::create(self::email()),
            'Billy',
            'Something',
            self::password(),
        );

        if (UserRole::USER !== $role) {
            $user->become($role, $category);
        }

        return $user;
    }
}
