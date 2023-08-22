<?php

declare(strict_types=1);

namespace TicketSystem\User\Application\GetOwnInfo;

use TicketSystem\Shared\Application\Command\Command;
use TicketSystem\User\Domain\GetUserInfo;
use TicketSystem\User\Domain\User;
use TicketSystem\User\Domain\UserId;

/**
 * @template-implements Command<string, GetOwnInfoCommandResponse>
 */
readonly class GetOwnInfoCommand implements Command
{
    public function __construct(
        private GetUserInfo $getUserInfo
    ) {
    }

    /**
     * @param string $request an uuid matching the user
     */
    public function execute($request): GetOwnInfoCommandResponse
    {
        $userId = UserId::create($request);

        $user = $this->getUserInfo->execute($userId);

        return GetOwnInfoCommandResponse::create(
            $user->id->id,
            $user->email->value
        );
    }
}
