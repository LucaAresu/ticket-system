<?php

declare(strict_types=1);

namespace Tests\Helpers\User\Domain;

use TicketSystem\Shared\Domain\Email;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;

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

    public static function user(): User
    {
        return User::create(
            UserId::create(self::userId()),
            Email::create(self::email()),
            self::password()
        );
    }
}
