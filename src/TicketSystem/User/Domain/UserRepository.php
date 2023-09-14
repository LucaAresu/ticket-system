<?php

declare(strict_types=1);

namespace TicketSystem\User\Domain;

use TicketSystem\Shared\Domain\Email;
use TicketSystem\Shared\Domain\Repository;

/**
 * @template-extends Repository<User, UserId>
 */
interface UserRepository extends Repository
{
    public function ofEmail(Email $email): null|User;
}
