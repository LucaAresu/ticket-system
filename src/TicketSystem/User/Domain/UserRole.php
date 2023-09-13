<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

enum UserRole: string
{
    case USER = 'USER';
    case MANAGER = 'MANAGER';
    case OPERATOR = 'OPERATOR';
    case SUPER_OPERATOR = 'SUPER_OPERATOR';

    public static function operatorRoles(): array
    {
        return [
            self::OPERATOR,
            self::SUPER_OPERATOR,
        ];
    }
}
