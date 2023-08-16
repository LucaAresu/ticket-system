<?php

declare(strict_types=1);

namespace TicketSystem\Shared\Application\Command;

/**
 * @template I
 * @template O
 */
interface Command
{
    /**
     * @param I $request
     *
     * @return O
     */
    public function execute($request);
}
